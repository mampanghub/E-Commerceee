<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Invoice #{{ str_pad($order->order_id, 6, '0', STR_PAD_LEFT) }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
    
    body {
      margin: 0;
      padding: 0;
      background: #f8fafc;
      font-family: 'Inter', system-ui, Arial, sans-serif;
    }
    
    .container {
      max-width: 620px;
      margin: 40px auto;
      background: #ffffff;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.06);
      border: 1px solid #e2e8f0;
    }
  </style>
</head>
<body>

  <div class="container">
    
    <!-- Header -->
    <div style="background: linear-gradient(135deg, #00AA5B 0%, #008F4B 100%); padding: 32px 40px; color: white;">
      <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
          <div style="font-size: 28px; font-weight: 700; letter-spacing: -1px;">MampangPedia</div>
          <div style="font-size: 14px; opacity: 0.9; margin-top: 4px;">Toko Online Terpercaya</div>
        </div>
        <div style="text-align: right;">
          <div style="font-size: 13px; opacity: 0.85; text-transform: uppercase; letter-spacing: 1px;">Invoice</div>
          <div style="font-size: 26px; font-weight: 700; letter-spacing: -1.5px; margin-top: 4px;">
            #{{ str_pad($order->order_id, 6, '0', STR_PAD_LEFT) }}
          </div>
        </div>
      </div>
    </div>

    <!-- Status -->
    <div style="background: #f0fdf4; padding: 16px 40px; border-bottom: 1px solid #e2e8f0;">
      <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
          <span style="background: #dcfce7; color: #166534; padding: 6px 16px; border-radius: 9999px; font-weight: 600; font-size: 14px;">
            ✓ Pembayaran Berhasil
          </span>
        </div>
        <div style="text-align: right; font-size: 14px;">
          <span style="color: #64748b;">Tanggal Pesanan:</span><br>
          <strong style="color: #1e2937;">{{ $order->created_at->format('d F Y, H:i') }} WIB</strong>
        </div>
      </div>
    </div>

    <div style="padding: 40px;">

      <!-- Info Pembeli & Penjual -->
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 32px;">
        <div>
          <div style="font-size: 13px; font-weight: 600; color: #64748b; margin-bottom: 8px;">Pembeli</div>
          <div style="color: #1e2937; line-height: 1.6;">
            <strong>{{ $order->user->name }}</strong><br>
            {{ $order->no_telp_penerima ?? '-' }}<br>
          </div>
        </div>
        <div>
          <div style="font-size: 13px; font-weight: 600; color: #64748b; margin-bottom: 8px;">Penjual</div>
          <div style="color: #1e2937; line-height: 1.6;">
            <strong>MampangPedia Official</strong><br>
            Depok, Jawa Barat
          </div>
        </div>
      </div>

      <!-- Alamat Pengiriman -->
      <div style="margin-bottom: 32px;">
        <div style="font-size: 13px; font-weight: 600; color: #64748b; margin-bottom: 10px;">Alamat Pengiriman</div>
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; line-height: 1.7; color: #334155;">
          <strong>{{ $order->nama_penerima ?? $order->user->name }}</strong><br>
          {{ $order->shipping_address ?? '-' }}<br>
          {{ $order->no_telp_penerima ?? '-' }}
        </div>
      </div>

      <!-- Daftar Barang -->
      <div style="margin-bottom: 32px;">
        <div style="font-size: 13px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px;">
          Daftar Pesanan
        </div>
        
        <table style="width:100%; border-collapse: collapse;">
          @foreach($order->items as $item)
          <tr style="border-bottom: 1px solid #f1f5f9;">
            <td style="padding: 18px 0; vertical-align: top;">
              <div style="font-weight: 500; color: #1e2937;">
                {{ $item->product->nama_produk ?? '-' }}
              </div>
              @if($item->variant)
              <div style="font-size: 13px; color: #64748b; margin-top: 4px;">
                Varian: {{ $item->variant->nama_varian }}
              </div>
              @endif
            </td>
            <td style="padding: 18px 0; text-align: center; color: #64748b; vertical-align: top;">
              x{{ $item->jumlah }}
            </td>
            <td style="padding: 18px 0; text-align: right; font-weight: 600; color: #1e2937; vertical-align: top;">
              Rp {{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}
            </td>
          </tr>
          @endforeach
        </table>
      </div>

      <!-- Ringkasan Pembayaran -->
      @php
        $subtotal = $order->total_harga - $order->ongkir - 2500;
      @endphp
      
      <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
          <span style="color: #64748b;">Subtotal</span>
          <span style="color: #334155;">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
          <span style="color: #64748b;">Ongkos Kirim</span>
          <span style="color: #334155;">Rp {{ number_format($order->ongkir, 0, ',', '.') }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 16px;">
          <span style="color: #64748b;">Biaya Layanan</span>
          <span style="color: #334155;">Rp 2.500</span>
        </div>
        
        <div style="border-top: 2px solid #cbd5e1; padding-top: 20px; margin-top: 8px;">
          <div style="display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 18px; font-weight: 700; color: #1e2937;">Total Pembayaran</span>
            <span style="font-size: 24px; font-weight: 700; color: #00AA5B;">
              Rp {{ number_format($order->total_harga, 0, ',', '.') }}
            </span>
          </div>
        </div>
      </div>

    </div>

    <!-- Footer -->
    <div style="background: #f8fafc; padding: 32px 40px; text-align: center; border-top: 1px solid #e2e8f0;">
      <p style="margin: 0; color: #64748b; font-size: 15px;">
        Terima kasih telah berbelanja di <strong style="color:#00AA5B;">MampangPedia</strong> 🙏
      </p>
      <p style="margin: 12px 0 0; color: #94a3b8; font-size: 13px;">
        Ada pertanyaan? Hubungi kami di 
        <a href="mailto:support@mampangpedia.id" style="color: #00AA5B; text-decoration: none;">support@mampangpedia.id</a>
      </p>
      <div style="margin-top: 24px; padding-top: 20px; border-top: 1px solid #e2e8f0; color: #94a3b8; font-size: 12px;">
        Invoice ini dibuat secara otomatis • {{ now()->format('d F Y') }}
      </div>
    </div>

  </div>

</body>
</html>