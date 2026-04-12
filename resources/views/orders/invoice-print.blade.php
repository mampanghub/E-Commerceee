<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Invoice #{{ str_pad($order->order_id, 6, '0', STR_PAD_LEFT) }}</title>
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { background:#f1f5f9; font-family:Arial,Helvetica,sans-serif; }
    #btn-wrap { max-width:600px;margin:24px auto 12px;text-align:right;padding:0 8px; }
    #btn-cetak { background:#0f172a;color:#fff;font-family:Arial,Helvetica,sans-serif;font-size:13px;font-weight:700;border:none;padding:11px 26px;border-radius:8px;cursor:pointer;letter-spacing:0.3px; }
    #btn-cetak:hover { background:#1e293b; }
    @media print {
      @page { size:A4; margin:10mm; }
      body { background:white !important; }
      #btn-wrap { display:none !important; }
    }
  </style>
</head>
<body>

  <div id="btn-wrap">
    <button id="btn-cetak">🖨️ Cetak / Simpan PDF</button>
  </div>

  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f1f5f9;padding:0 0 40px;">
    <tr><td align="center">
      <table width="600" cellpadding="0" cellspacing="0" border="0"
        style="background:#ffffff;border-radius:12px;border:1px solid #e2e8f0;overflow:hidden;max-width:600px;width:100%;">

        {{-- HEADER --}}
        <tr>
          <td style="padding:28px 36px 24px;border-bottom:1px solid #f1f5f9;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>
              <td>
                <div style="font-size:20px;font-weight:800;color:#0f172a;letter-spacing:-0.5px;font-family:Arial,Helvetica,sans-serif;">MampangPedia</div>
                <div style="font-size:12px;color:#94a3b8;margin-top:3px;font-family:Arial,Helvetica,sans-serif;">Toko Online Terpercaya</div>
              </td>
              <td align="right" valign="top">
                <div style="font-size:10px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:#94a3b8;font-family:Arial,Helvetica,sans-serif;">Invoice</div>
                <div style="font-size:22px;font-weight:800;color:#0f172a;letter-spacing:-1px;margin-top:3px;font-family:Arial,Helvetica,sans-serif;">
                  #{{ str_pad($order->order_id, 6, '0', STR_PAD_LEFT) }}
                </div>
              </td>
            </tr></table>
          </td>
        </tr>

        {{-- STATUS --}}
        <tr>
          <td style="background:#f8fafc;padding:13px 36px;border-bottom:1px solid #f1f5f9;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>
              <td>
                <table cellpadding="0" cellspacing="0" border="0"><tr>
                  <td style="width:8px;padding-right:7px;">
                    <div style="width:7px;height:7px;background:#16a34a;border-radius:50%;-webkit-print-color-adjust:exact;print-color-adjust:exact;"></div>
                  </td>
                  <td style="font-size:12px;font-weight:700;color:#16a34a;font-family:Arial,Helvetica,sans-serif;">Pembayaran Berhasil</td>
                </tr></table>
              </td>
              <td align="right">
                <div style="font-size:11px;color:#94a3b8;font-family:Arial,Helvetica,sans-serif;">Tanggal Pesanan</div>
                <div style="font-size:12px;font-weight:600;color:#475569;margin-top:2px;font-family:Arial,Helvetica,sans-serif;">{{ $order->created_at->format('d F Y, H:i') }} WIB</div>
              </td>
            </tr></table>
          </td>
        </tr>

        {{-- BODY --}}
        <tr><td style="padding:28px 36px;">

          {{-- Pembeli & Penjual --}}
          <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;padding-bottom:24px;border-bottom:1px solid #f1f5f9;">
            <tr>
              <td width="50%" valign="top" style="padding-right:12px;">
                <div style="font-size:10px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:#94a3b8;margin-bottom:7px;font-family:Arial,Helvetica,sans-serif;">Pembeli</div>
                <div style="font-size:13px;font-weight:700;color:#0f172a;font-family:Arial,Helvetica,sans-serif;">{{ $order->user->name }}</div>
                <div style="font-size:13px;color:#475569;margin-top:3px;font-family:Arial,Helvetica,sans-serif;">{{ $order->no_telp_penerima ?? '-' }}</div>
              </td>
              <td width="50%" valign="top" style="padding-left:12px;">
                <div style="font-size:10px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:#94a3b8;margin-bottom:7px;font-family:Arial,Helvetica,sans-serif;">Penjual</div>
                <div style="font-size:13px;font-weight:700;color:#0f172a;font-family:Arial,Helvetica,sans-serif;">MampangPedia Official</div>
                <div style="font-size:13px;color:#475569;margin-top:3px;font-family:Arial,Helvetica,sans-serif;">Depok, Jawa Barat</div>
              </td>
            </tr>
          </table>

          {{-- Alamat --}}
          <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;padding-bottom:24px;border-bottom:1px solid #f1f5f9;">
            <tr><td>
              <div style="font-size:10px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:#94a3b8;margin-bottom:8px;font-family:Arial,Helvetica,sans-serif;">Alamat Pengiriman</div>
              <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #e2e8f0;border-radius:10px;">
                <tr><td style="padding:14px 16px;">
                  <div style="font-size:13px;font-weight:700;color:#0f172a;font-family:Arial,Helvetica,sans-serif;">{{ $order->nama_penerima ?? $order->user->name }}</div>
                  <div style="font-size:13px;color:#475569;margin-top:4px;line-height:1.6;font-family:Arial,Helvetica,sans-serif;">{{ $order->shipping_address ?? '-' }}</div>
                  <div style="font-size:13px;color:#475569;margin-top:2px;font-family:Arial,Helvetica,sans-serif;">{{ $order->no_telp_penerima ?? '-' }}</div>
                </td></tr>
              </table>
            </td></tr>
          </table>

          {{-- Daftar Pesanan --}}
          <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;">
            <tr>
              <td colspan="3" style="padding-bottom:10px;border-bottom:1px solid #e2e8f0;">
                <div style="font-size:10px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:#94a3b8;font-family:Arial,Helvetica,sans-serif;">Daftar Pesanan</div>
              </td>
            </tr>
            <tr>
              <td style="padding:10px 0 6px;font-size:10px;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;color:#cbd5e1;font-family:Arial,Helvetica,sans-serif;">Produk</td>
              <td width="50" align="center" style="padding:10px 0 6px;font-size:10px;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;color:#cbd5e1;font-family:Arial,Helvetica,sans-serif;">Qty</td>
              <td align="right" style="padding:10px 0 6px;font-size:10px;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;color:#cbd5e1;font-family:Arial,Helvetica,sans-serif;">Harga</td>
            </tr>
            @foreach($order->items as $item)
            <tr>
              <td style="padding:11px 0;border-top:1px solid #f8fafc;vertical-align:top;">
                <div style="font-size:13px;font-weight:600;color:#0f172a;font-family:Arial,Helvetica,sans-serif;">{{ $item->product->nama_produk ?? '-' }}</div>
                @if($item->variant)
                <div style="font-size:11px;color:#94a3b8;margin-top:2px;font-family:Arial,Helvetica,sans-serif;">Varian: {{ $item->variant->nama_varian }}</div>
                @endif
              </td>
              <td width="50" align="center" style="padding:11px 0;border-top:1px solid #f8fafc;vertical-align:top;font-size:13px;color:#94a3b8;font-family:Arial,Helvetica,sans-serif;">x{{ $item->jumlah }}</td>
              <td align="right" style="padding:11px 0;border-top:1px solid #f8fafc;vertical-align:top;font-size:13px;font-weight:700;color:#0f172a;font-family:Arial,Helvetica,sans-serif;white-space:nowrap;">
                Rp {{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}
              </td>
            </tr>
            @endforeach
          </table>

          {{-- Summary --}}
          @php $subtotal = $order->total_harga - $order->ongkir - 2500; @endphp
          <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
            <tr>
              <td style="padding:14px 18px 4px;font-size:13px;color:#94a3b8;font-family:Arial,Helvetica,sans-serif;">Subtotal</td>
              <td align="right" style="padding:14px 18px 4px;font-size:13px;color:#475569;font-family:Arial,Helvetica,sans-serif;white-space:nowrap;">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
              <td style="padding:4px 18px;font-size:13px;color:#94a3b8;font-family:Arial,Helvetica,sans-serif;">Ongkos Kirim</td>
              <td align="right" style="padding:4px 18px;font-size:13px;color:#475569;font-family:Arial,Helvetica,sans-serif;white-space:nowrap;">Rp {{ number_format($order->ongkir, 0, ',', '.') }}</td>
            </tr>
            <tr>
              <td style="padding:4px 18px 14px;font-size:13px;color:#94a3b8;font-family:Arial,Helvetica,sans-serif;">Biaya Layanan</td>
              <td align="right" style="padding:4px 18px 14px;font-size:13px;color:#475569;font-family:Arial,Helvetica,sans-serif;white-space:nowrap;">Rp 2.500</td>
            </tr>
            <tr><td colspan="2" style="border-top:1px solid #e2e8f0;padding:0;font-size:0;line-height:0;">&nbsp;</td></tr>
            <tr style="background:#f8fafc;">
              <td style="padding:16px 18px;font-size:13px;font-weight:700;color:#0f172a;font-family:Arial,Helvetica,sans-serif;">Total Pembayaran</td>
              <td align="right" style="padding:16px 18px;font-size:20px;font-weight:800;color:#0f172a;font-family:Arial,Helvetica,sans-serif;white-space:nowrap;">
                Rp {{ number_format($order->total_harga, 0, ',', '.') }}
              </td>
            </tr>
          </table>

        </td></tr>

        {{-- FOOTER --}}
        <tr>
          <td align="center" style="padding:20px 36px 26px;border-top:1px solid #f1f5f9;">
            <div style="font-size:12px;color:#94a3b8;margin-bottom:4px;font-family:Arial,Helvetica,sans-serif;">
              Terima kasih telah berbelanja di <span style="font-weight:700;color:#0f172a;">MampangPedia</span>
            </div>

            <div style="font-size:11px;color:#cbd5e1;margin-top:10px;font-family:Arial,Helvetica,sans-serif;">
                &middot; {{ now()->format('d F Y') }}
            </div>
          </td>
        </tr>

      </table>
    </td></tr>
  </table>

  <script>
    document.getElementById('btn-cetak').addEventListener('click', function() {
      window.print();
    });
  </script>

</body>
</html>