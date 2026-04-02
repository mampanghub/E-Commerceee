<?php

namespace App\Services;

use App\Models\ProvinceCoordinate;
use App\Models\ShippingZone;
use App\Models\ShippingOption;
use App\Models\ShippingSetting;

class ShippingService
{
    // Fallback default kalau DB belum ada isinya
    const DEFAULT_TARIF_PER_KM    = 1500;
    const DEFAULT_TARIF_PER_500GR = 500;
    const DEFAULT_JARAK_MINIMUM   = 5;

    const PULAU_MAP = [
        'jawa'       => ['31', '32', '33', '34', '35', '36'],
        'sumatera'   => ['11', '12', '13', '14', '15', '16', '17', '18', '19', '21'],
        'kalimantan' => ['61', '62', '63', '64', '65'],
        'sulawesi'   => ['71', '72', '73', '74', '75', '76'],
        'bali_nusa'  => ['51', '52', '53'],
        'maluku'     => ['81', '82'],
        'papua'      => ['91', '92', '94', '95', '96'],
    ];

    // ─────────────────────────────────────────────
    // AMBIL SETTING DINAMIS
    // ─────────────────────────────────────────────

    private function tarifPerKm(): float
    {
        return ShippingSetting::get('tarif_per_km', self::DEFAULT_TARIF_PER_KM);
    }

    private function tarifPer500gr(): float
    {
        return ShippingSetting::get('tarif_per_500gr', self::DEFAULT_TARIF_PER_500GR);
    }

    private function jarakMinimum(): float
    {
        return ShippingSetting::get('jarak_minimum', self::DEFAULT_JARAK_MINIMUM);
    }

    // ─────────────────────────────────────────────
    // HITUNG JARAK (Haversine Formula)
    // ─────────────────────────────────────────────

    public function hitungJarakKm(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2)
           + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
           * sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    private function getKoordinat(string $provinceCode): ProvinceCoordinate
    {
        $coord = ProvinceCoordinate::where('province_code', $provinceCode)->first();

        if (!$coord) {
            throw new \RuntimeException("Koordinat provinsi code={$provinceCode} tidak ditemukan. Jalankan ProvinceCoordinateSeeder.");
        }

        return $coord;
    }

    // ─────────────────────────────────────────────
    // HITUNG ONGKIR
    // ─────────────────────────────────────────────

    public function hitungOngkirDasar(string $provinsiToko, string $provinsiPembeli, float $beratGram): int
    {
        $koordinatToko    = $this->getKoordinat($provinsiToko);
        $koordinatPembeli = $this->getKoordinat($provinsiPembeli);

        $jarakKm = $this->hitungJarakKm(
            $koordinatToko->latitude,
            $koordinatToko->longitude,
            $koordinatPembeli->latitude,
            $koordinatPembeli->longitude
        );

        $jarakKm    = max($jarakKm, $this->jarakMinimum());
        $biayaJarak = (int) ceil($jarakKm) * $this->tarifPerKm();

        $kelipatan  = max(0, ceil($beratGram / 500) - 1);
        $biayaBerat = $kelipatan * $this->tarifPer500gr();

        return (int) ($biayaJarak + $biayaBerat);
    }

    public function hitungOngkirFinal(int $ongkirDasar, ShippingOption $option): int
    {
        $tambahan = $ongkirDasar * ($option->persen_tambahan / 100);
        return (int) ceil($ongkirDasar + $tambahan);
    }

    // ─────────────────────────────────────────────
    // ZONE
    // ─────────────────────────────────────────────

    public function tentukanZona(string $provinsiToko, string $provinsiPembeli): ShippingZone
    {
        if ($provinsiToko === $provinsiPembeli) {
            $tipe = 'dalam_provinsi';
        } elseif ($this->samaPulau($provinsiToko, $provinsiPembeli)) {
            $tipe = 'luar_provinsi';
        } else {
            $tipe = 'luar_pulau';
        }

        return ShippingZone::where('tipe', $tipe)->firstOrFail();
    }

    public function hitungEstimasiRange(ShippingZone $zone, ShippingOption $option): array
    {
        $min = max($zone->estimasi_hari_base - $option->kurang_hari, $zone->estimasi_hari_min);
        $max = max($zone->estimasi_hari_base_max - $option->kurang_hari, $zone->estimasi_hari_min_max);

        if ($max < $min) $max = $min;

        return [$min, $max];
    }

    public function formatEstimasi(ShippingZone $zone, ShippingOption $option): string
    {
        [$min, $max] = $this->hitungEstimasiRange($zone, $option);
        return $min === $max ? "{$min} hari" : "{$min}-{$max} hari";
    }

    public function formatTanggalTiba(ShippingZone $zone, ShippingOption $option): string
    {
        [$min, $max] = $this->hitungEstimasiRange($zone, $option);

        $tglMin = now()->addDays($min);
        $tglMax = now()->addDays($max);

        if ($min === $max) {
            return $tglMin->translatedFormat('d M Y');
        }

        if ($tglMin->month === $tglMax->month) {
            return $tglMin->format('d') . ' - ' . $tglMax->translatedFormat('d M Y');
        }

        return $tglMin->translatedFormat('d M') . ' - ' . $tglMax->translatedFormat('d M Y');
    }

    // ─────────────────────────────────────────────
    // MAIN
    // ─────────────────────────────────────────────

    public function getOpsiPengiriman(string $provinsiToko, string $provinsiPembeli, float $beratGram): array
    {
        $zone        = $this->tentukanZona($provinsiToko, $provinsiPembeli);
        $ongkirDasar = $this->hitungOngkirDasar($provinsiToko, $provinsiPembeli, $beratGram);
        $options     = ShippingOption::orderBy('kurang_hari', 'asc')->get();

        try {
            $koordToko    = $this->getKoordinat($provinsiToko);
            $koordPembeli = $this->getKoordinat($provinsiPembeli);
            $jarakKm      = (int) ceil(max(
                $this->hitungJarakKm(
                    $koordToko->latitude, $koordToko->longitude,
                    $koordPembeli->latitude, $koordPembeli->longitude
                ),
                $this->jarakMinimum()
            ));
        } catch (\Exception $e) {
            $jarakKm = null;
        }

        return [
            'zone'     => $zone,
            'jarak_km' => $jarakKm,
            'options'  => $options->map(function ($opt) use ($zone, $ongkirDasar) {
                [$min, $max] = $this->hitungEstimasiRange($zone, $opt);
                return [
                    'option_id'       => $opt->option_id,
                    'label'           => $opt->label,
                    'kurang_hari'     => $opt->kurang_hari,
                    'estimasi_min'    => $min,
                    'estimasi_max'    => $max,
                    'estimasi_label'  => $this->formatEstimasi($zone, $opt),
                    'tanggal_tiba'    => $this->formatTanggalTiba($zone, $opt),
                    'persen_tambahan' => $opt->persen_tambahan,
                    'ongkir'          => $this->hitungOngkirFinal($ongkirDasar, $opt),
                ];
            })->toArray(),
        ];
    }

    // ─────────────────────────────────────────────
    // HELPER
    // ─────────────────────────────────────────────

    private function samaPulau(string $prov1, string $prov2): bool
    {
        foreach (self::PULAU_MAP as $kodes) {
            if (in_array($prov1, $kodes) && in_array($prov2, $kodes)) {
                return true;
            }
        }
        return false;
    }
}
