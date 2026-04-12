<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Resi #{{ str_pad($order->order_id, 6, '0', STR_PAD_LEFT) }}</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { background: #f1f5f9; font-family: Arial, Helvetica, sans-serif; font-size: 13px; color: #000; }

    #btn-wrap {
      max-width: 680px;
      margin: 24px auto 12px;
      display: flex;
      gap: 8px;
      justify-content: flex-end;
      padding: 0 8px;
    }
    #btn-cetak {
      background: #0f172a; color: #fff;
      font-size: 13px; font-weight: 700;
      border: none; padding: 11px 24px;
      border-radius: 8px; cursor: pointer;
    }
    #btn-back {
      background: #fff; color: #0f172a;
      font-size: 13px; font-weight: 700;
      border: 1px solid #e2e8f0; padding: 11px 24px;
      border-radius: 8px; cursor: pointer;
      text-decoration: none; display: inline-flex; align-items: center;
    }

    .resi-wrap {
      max-width: 680px;
      margin: 0 auto 40px;
      background: #fff;
      border: 2px solid #000;
    }

    .resi-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 16px;
      border-bottom: 2px solid #000;
    }
    .brand-name { font-size: 22px; font-weight: 900; color: #000; letter-spacing: -0.5px; }
    .brand-sub { font-size: 9px; color: #555; margin-top: 1px; }
    .layanan-badge { font-size: 28px; font-weight: 900; color: #000; letter-spacing: 2px; }

    .info-row { display: flex; border-bottom: 1.5px dashed #000; }
    .info-cell {
      padding: 8px 12px;
      border-right: 1.5px solid #000;
      font-size: 12px;
      font-weight: 700;
      display: flex;
      align-items: center;
    }
    .info-cell:last-child { border-right: none; }
    .info-cell.big { font-size: 20px; font-weight: 900; min-width: 120px; }

    .barcode-area {
      padding: 10px 16px 8px;
      border-bottom: 1.5px dashed #000;
    }
    .barcode-lines {
      display: flex;
      align-items: flex-end;
      height: 56px;
      width: 100%;
      overflow: hidden;
    }
    .barcode-number {
      font-family: 'Courier New', monospace;
      font-size: 10px;
      letter-spacing: 3px;
      text-align: center;
      margin-top: 4px;
    }

    .alamat-row { display: flex; border-bottom: 1.5px dashed #000; }
    .alamat-penerima { flex: 1.3; padding: 12px 14px; border-right: 1.5px solid #000; }
    .alamat-pengirim { flex: 1; padding: 12px 14px; }
    .alamat-label { font-size: 11px; font-weight: 700; margin-bottom: 4px; }
    .alamat-nama { font-size: 15px; font-weight: 900; margin-bottom: 3px; }
    .alamat-telp { font-size: 13px; font-weight: 700; margin-bottom: 4px; }
    .alamat-detail { font-size: 11px; line-height: 1.5; text-transform: uppercase; }

    .meta-row { display: flex; border-bottom: 1.5px dashed #000; }
    .meta-cell {
      flex: 1;
      padding: 6px 12px;
      border-right: 1.5px solid #000;
      font-size: 11px;
      font-weight: 700;
      text-align: center;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .meta-cell:last-child { border-right: none; }

    .info-bawah { display: flex; border-bottom: 1.5px dashed #000; align-items: stretch; }
    .info-bawah-left { flex: 1; padding: 12px 14px; border-right: 1.5px solid #000; }
    .info-bawah-left p { font-size: 13px; font-weight: 700; margin-bottom: 4px; }
    .info-bawah-right { display: flex; flex-direction: column; gap: 6px; padding: 10px 12px; justify-content: center; }
    .rts-badge {
      border: 2px solid #000;
      padding: 6px 14px;
      font-size: 13px;
      font-weight: 900;
      text-align: center;
      letter-spacing: 1px;
      min-width: 110px;
    }

    .qr-box {
      width: 76px; height: 76px;
      border: 2px solid #000;
      display: grid;
      grid-template-columns: repeat(6,1fr);
      grid-template-rows: repeat(6,1fr);
      gap: 1px;
      padding: 4px;
    }
    .qr-box span { background: #000; border-radius: 1px; }
    .qr-box span.w { background: #fff; }

    .produk-table { width: 100%; border-collapse: collapse; font-size: 12px; }
    .produk-table th {
      background: #000; color: #fff;
      padding: 6px 10px;
      text-align: left;
      font-size: 11px;
      font-weight: 700;
    }
    .produk-table td {
      padding: 8px 10px;
      border-bottom: 1px solid #e2e8f0;
      vertical-align: top;
    }
    .produk-table tr:last-child td { border-bottom: none; }

    .catatan-row {
      padding: 8px 14px;
      font-size: 11px;
      border-top: 1.5px dashed #000;
      color: #444;
    }

    @media print {
      @page { size: A5; margin: 5mm; }
      body { background: white !important; }
      #btn-wrap { display: none !important; }
      .resi-wrap { margin: 0; max-width: 100%; }
      * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
  </style>
</head>
<body>

  <div id="btn-wrap">
    <a href="{{ route('orders.show', $order->order_id) }}" id="btn-back">← Kembali</a>
    <button id="btn-cetak">🖨️ Cetak Resi</button>
  </div>

  <div class="resi-wrap">

    {{-- HEADER --}}
    <div class="resi-header">
      <div>
        <div class="brand-name">MampangPedia</div>
        <div class="brand-sub">mampangpedia.com</div>
      </div>
      <div class="layanan-badge">{{ strtoupper($order->shippingOption?->label ?? 'REG') }}</div>
      <div style="text-align:right;">
        <div style="font-size:20px; font-weight:900; letter-spacing:1px;">MPX</div>
        <div style="font-size:9px; color:#555;">Express</div>
      </div>
    </div>

    {{-- INFO ROW --}}
    <div class="info-row">
      <div class="info-cell big">ORD-{{ str_pad($order->order_id, 4, '0', STR_PAD_LEFT) }}</div>
      <div class="info-cell" style="flex:1;">{{ strtoupper($order->shippingZone?->nama_zona ?? 'REGULER') }}</div>
      <div class="info-cell">No. Order: &nbsp;<strong>{{ str_pad($order->order_id, 6, '0', STR_PAD_LEFT) }}</strong></div>
    </div>

    {{-- BARCODE --}}
    <div class="barcode-area">
      <div class="barcode-lines" id="barcode"></div>
      <div class="barcode-number">MP{{ str_pad($order->order_id, 12, '0', STR_PAD_LEFT) }}ID</div>
    </div>

    {{-- ALAMAT --}}
    <div class="alamat-row">
      <div class="alamat-penerima">
        <div class="alamat-label">Penerima:</div>
        <div class="alamat-nama">{{ $order->nama_penerima ?? $order->user->name }}</div>
        <div class="alamat-telp">{{ $order->no_telp_penerima ?? '-' }}</div>
        <div class="alamat-detail">{{ strtoupper($order->shipping_address ?? '-') }}</div>
      </div>
      <div class="alamat-pengirim">
        <div class="alamat-label">Pengirim:</div>
        <div class="alamat-nama" style="font-size:13px;">MampangPedia</div>
        <div class="alamat-telp" style="font-size:11px;">-</div>
        <div class="alamat-detail">DEPOK, JAWA BARAT</div>
      </div>
    </div>

    {{-- META --}}
    <div class="meta-row">
      <div class="meta-cell" style="max-width:120px;">CASHLESS</div>
      <div class="meta-cell" style="justify-content:flex-start; font-weight:400; text-transform:none; font-style:italic;">
        Pembeli tidak perlu bayar ongkir ke kurir
      </div>
    </div>

    {{-- INFO BAWAH --}}
    <div class="info-bawah">
      <div class="info-bawah-left">
        <p><strong>Berat:</strong> {{ number_format($order->berat_total_gram, 0, ',', '.') }} gr</p>
        <p><strong>No. Pesanan:</strong> {{ str_pad($order->order_id, 6, '0', STR_PAD_LEFT) }}</p>
        @if($order->catatan)
          <p style="margin-top:6px; font-size:11px; color:#555;"><strong>Catatan:</strong> {{ $order->catatan }}</p>
        @endif
      </div>
      <div style="padding:10px 12px; border-right:1.5px solid #000; display:flex; align-items:center;">
        <div class="qr-box">
          @php $qr = [1,1,1,1,1,0,1,0,0,1,0,1,1,0,0,1,1,0,1,1,1,0,0,1,1,0,0,0,1,1,0,1,1,1,1,0]; @endphp
          @foreach($qr as $cell)
            <span class="{{ $cell ? '' : 'w' }}"></span>
          @endforeach
        </div>
      </div>
      <div class="info-bawah-right">
        <div class="rts-badge">{{ $order->shippingZone?->kode ?? 'MP-01' }}</div>
        <div class="rts-badge">{{ strtoupper(substr($order->shippingOption?->label ?? 'REG', 0, 3)) }}-{{ str_pad($order->order_id, 3, '0', STR_PAD_LEFT) }}</div>
      </div>
    </div>

    {{-- PRODUK TABLE --}}
    <table class="produk-table">
      <thead>
        <tr>
          <th style="width:28px;">#</th>
          <th>Nama Produk</th>
          <th style="width:100px;">Variasi</th>
          <th style="width:40px; text-align:center;">Qty</th>
        </tr>
      </thead>
      <tbody>
        @foreach($order->items as $i => $item)
          <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $item->product->nama_produk ?? '-' }}</td>
            <td>{{ $item->variant?->nama_varian ?? '-' }}</td>
            <td style="text-align:center; font-weight:700;">{{ $item->jumlah }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    {{-- FOOTER --}}
    <div class="catatan-row">
      <strong>No. Pesanan:</strong> {{ str_pad($order->order_id, 6, '0', STR_PAD_LEFT) }}
      &nbsp;&middot;&nbsp;
      <strong>Dicetak:</strong> {{ now()->format('d/m/Y H:i') }}
    </div>

  </div>

  <script>
    document.getElementById('btn-cetak').addEventListener('click', () => window.print());

    (function() {
      const el = document.getElementById('barcode');
      const seed = {{ $order->order_id }};
      let html = '';
      for (let i = 0; i < 90; i++) {
        const isBlack = (i + Math.floor(seed / (i + 1))) % 3 !== 0;
        const w = i % 5 === 0 ? 4 : (i % 3 === 0 ? 3 : 2);
        const h = isBlack ? (60 + ((i * 13 + seed) % 40)) : 0;
        html += `<span style="width:${w}px;height:${h}%;background:#000;display:inline-block;"></span>`;
        if (!isBlack) html += `<span style="width:${w}px;height:100%;background:#fff;display:inline-block;"></span>`;
      }
      el.innerHTML = html;
    })();
  </script>

</body>
</html>