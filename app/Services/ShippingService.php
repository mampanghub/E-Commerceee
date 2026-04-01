<?php

namespace App\Services;

use App\Models\ShippingZone;
use App\Models\ShippingOption;

class ShippingService
{
    const PULAU_MAP = [
        'jawa'       => ['31', '32', '33', '34', '35', '36'],
        'sumatera'   => ['11', '12', '13', '14', '15', '16', '17', '18', '19', '21'],
        'kalimantan' => ['61', '62', '63', '64', '65'],
        'sulawesi'   => ['71', '72', '73', '74', '75', '76'],
        'bali_nusa'  => ['51', '52', '53'],
        'maluku'     => ['81', '82'],
        'papua'      => ['91', '92', '94', '95', '96'],
    ];

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

    /**
     * Hitung range estimasi hari: [min, max]
     * Tidak bisa kurang dari estimasi_hari_min / estimasi_hari_min_max
     */
    public function hitungEstimasiRange(ShippingZone $zone, ShippingOption $option): array
    {
        $min = max($zone->estimasi_hari_base - $option->kurang_hari, $zone->estimasi_hari_min);
        $max = max($zone->estimasi_hari_base_max - $option->kurang_hari, $zone->estimasi_hari_min_max);

        // Pastikan max >= min
        if ($max < $min) $max = $min;

        return [$min, $max];
    }

    /**
     * Format estimasi untuk ditampilkan ke user.
     * Contoh: "4-5 hari" atau "1 hari" kalau min == max
     */
    public function formatEstimasi(ShippingZone $zone, ShippingOption $option): string
    {
        [$min, $max] = $this->hitungEstimasiRange($zone, $option);
        return $min === $max ? "{$min} hari" : "{$min}-{$max} hari";
    }

    /**
     * Hitung tanggal tiba sebagai range string.
     * Contoh: "24 - 25 Mar 2026"
     */
    public function formatTanggalTiba(ShippingZone $zone, ShippingOption $option): string
    {
        [$min, $max] = $this->hitungEstimasiRange($zone, $option);

        $tglMin = now()->addDays($min);
        $tglMax = now()->addDays($max);

        if ($min === $max) {
            return $tglMin->translatedFormat('d M Y');
        }

        // Kalau bulan sama → "24 - 25 Mar 2026"
        if ($tglMin->month === $tglMax->month) {
            return $tglMin->format('d') . ' - ' . $tglMax->translatedFormat('d M Y');
        }

        // Beda bulan → "28 Mar - 2 Apr 2026"
        return $tglMin->translatedFormat('d M') . ' - ' . $tglMax->translatedFormat('d M Y');
    }

    public function hitungOngkirDasar(ShippingZone $zone, float $beratGram): int
    {
        $kelipatan  = max(0, ceil($beratGram / 500) - 1);
        $biayaBerat = $kelipatan * $zone->harga_per_500gr;
        return (int) ($zone->harga_dasar + $biayaBerat);
    }

    public function hitungOngkirFinal(int $ongkirDasar, ShippingOption $option): int
    {
        $tambahan = $ongkirDasar * ($option->persen_tambahan / 100);
        return (int) ceil($ongkirDasar + $tambahan);
    }

    public function getOpsiPengiriman(string $provinsiToko, string $provinsiPembeli, float $beratGram): array
    {
        $zone        = $this->tentukanZona($provinsiToko, $provinsiPembeli);
        $ongkirDasar = $this->hitungOngkirDasar($zone, $beratGram);
        $options     = ShippingOption::orderBy('kurang_hari', 'asc')->get();

        return [
            'zone'    => $zone,
            'options' => $options->map(function ($opt) use ($zone, $ongkirDasar) {
                [$min, $max] = $this->hitungEstimasiRange($zone, $opt);
                return [
                    'option_id'        => $opt->option_id,
                    'label'            => $opt->label,
                    'kurang_hari'      => $opt->kurang_hari,
                    'estimasi_min'     => $min,
                    'estimasi_max'     => $max,
                    'estimasi_label'   => $this->formatEstimasi($zone, $opt),
                    'tanggal_tiba'     => $this->formatTanggalTiba($zone, $opt),
                    'persen_tambahan'  => $opt->persen_tambahan,
                    'ongkir'           => $this->hitungOngkirFinal($ongkirDasar, $opt),
                ];
            })->toArray(),
        ];
    }

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