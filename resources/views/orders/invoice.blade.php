<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_id }} — MampangPedia</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f1f5f9;
            color: #1e293b;
            font-size: 14px;
        }

        .page-wrap {
            max-width: 780px;
            margin: 40px auto;
            padding: 0 16px 60px;
        }

        /* Top bar actions (tidak ikut print) */
        .actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 700;
            color: #64748b;
            text-decoration: none;
            padding: 8px 16px;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            transition: all .2s;
        }

        .btn-back:hover {
            color: #1d4ed8;
            border-color: #1d4ed8;
        }

        .btn-print {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            background: #1d4ed8;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            transition: background .2s;
        }

        .btn-print:hover {
            background: #1e40af;
        }

        /* Invoice card */
        .invoice {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, .07);
            overflow: hidden;
        }

        /* Header */
        .invoice-header {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e3a8a 100%);
            padding: 36px 40px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .brand {
            color: #fff;
        }

        .brand-name {
            font-size: 26px;
            font-weight: 900;
            letter-spacing: -0.5px;
        }

        .brand-name span {
            color: #93c5fd;
        }

        .brand-sub {
            font-size: 12px;
            color: #93c5fd;
            margin-top: 4px;
        }

        .invoice-meta {
            text-align: right;
            color: #bfdbfe;
        }

        .invoice-label {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #93c5fd;
        }

        .invoice-number {
            font-size: 22px;
            font-weight: 900;
            color: #fff;
            margin-top: 2px;
        }

        .invoice-date {
            font-size: 12px;
            color: #93c5fd;
            margin-top: 4px;
        }

        /* Status badge */
        .status-bar {
            padding: 12px 40px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 14px;
            border-radius: 100px;
            font-size: 12px;
            font-weight: 700;
        }

        .status-dibayar {
            background: #dcfce7;
            color: #16a34a;
        }

        .status-dikemas {
            background: #fef9c3;
            color: #ca8a04;
        }

        .status-dikirim {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .status-selesai {
            background: #ede9fe;
            color: #7c3aed;
        }

        .status-batal {
            background: #fee2e2;
            color: #dc2626;
        }

        .status-menunggu {
            background: #f1f5f9;
            color: #64748b;
        }

        /* Body */
        .invoice-body {
            padding: 36px 40px;
        }

        /* Info grid */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 32px;
        }

        .info-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 20px;
        }

        .info-box-title {
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 10px;
        }

        .info-box p {
            font-size: 13px;
            color: #475569;
            line-height: 1.7;
        }

        .info-box strong {
            font-weight: 800;
            color: #1e293b;
            display: block;
            margin-bottom: 2px;
        }

        /* Items table */
        .items-title {
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        thead tr {
            background: #f1f5f9;
        }

        thead th {
            padding: 10px 14px;
            text-align: left;
            font-size: 11px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .8px;
        }

        thead th:last-child {
            text-align: right;
        }

        tbody tr {
            border-bottom: 1px solid #f1f5f9;
        }

        tbody tr:last-child {
            border-bottom: none;
        }

        tbody td {
            padding: 14px;
            font-size: 13px;
            color: #334155;
            vertical-align: middle;
        }

        tbody td:last-child {
            text-align: right;
            font-weight: 700;
            color: #1e293b;
        }

        .product-name {
            font-weight: 700;
            color: #1e293b;
        }

        .product-variant {
            font-size: 11px;
            color: #6366f1;
            font-weight: 600;
            margin-top: 2px;
        }

        .qty-badge {
            display: inline-block;
            background: #f1f5f9;
            border-radius: 6px;
            padding: 2px 8px;
            font-size: 12px;
            font-weight: 700;
            color: #64748b;
        }

        /* Totals */
        .totals {
            margin-left: auto;
            width: 300px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 13px;
            color: #64748b;
            border-bottom: 1px dashed #e2e8f0;
        }

        .total-row:last-child {
            border-bottom: none;
        }

        .total-row.grand {
            padding-top: 14px;
            font-size: 16px;
            font-weight: 900;
            color: #1e293b;
        }

        .total-row.grand .amount {
            color: #1d4ed8;
        }

        /* Kurir info */
        .kurir-box {
            margin-top: 28px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 14px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .kurir-icon {
            width: 40px;
            height: 40px;
            background: #dbeafe;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .kurir-info strong {
            font-size: 13px;
            font-weight: 800;
            color: #1e293b;
            display: block;
        }

        .kurir-info span {
            font-size: 12px;
            color: #3b82f6;
            font-weight: 600;
        }

        /* Footer */
        .invoice-footer {
            border-top: 1px solid #e2e8f0;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8fafc;
        }

        .footer-note {
            font-size: 11px;
            color: #94a3b8;
        }

        .footer-brand {
            font-size: 12px;
            font-weight: 800;
            color: #1d4ed8;
        }

        /* ── PRINT STYLES ── */
        @media print {
            body {
                background: #fff;
            }

            .page-wrap {
                margin: 0;
                padding: 0;
            }

            .actions {
                display: none;
            }

            .invoice {
                border-radius: 0;
                box-shadow: none;
            }

            .btn-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="page-wrap">

        {{-- Action bar --}}
        <div class="actions">
            <a href="{{ route('orders.history') }}" class="btn-back">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
            <button class="btn-print" onclick="window.print()">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print Invoice
            </button>
        </div>

        <div class="invoice">

            {{-- Header --}}
            <div class="invoice-header">
                <div class="brand">
                    <div class="brand-name">Mampang<span>Pedia</span></div>
                    <div class="brand-sub">marketplace.mampangpedia.id</div>
                </div>
                <div class="invoice-meta">
                    <div class="invoice-label">Invoice</div>
                    <div class="invoice-number">#{{ str_pad($order->order_id, 6, '0', STR_PAD_LEFT) }}</div>
                    <div class="invoice-date">{{ $order->created_at->format('d F Y, H:i') }} WIB</div>
                </div>
            </div>

            {{-- Status --}}
            <div class="status-bar">
                <span>Status:</span>
                @php
                    $statusMap = [
                        'menunggu' => ['label' => 'Menunggu Pembayaran', 'class' => 'status-menunggu'],
                        'dibayar' => ['label' => 'Sudah Dibayar', 'class' => 'status-dibayar'],
                        'dikemas' => ['label' => 'Sedang Dikemas', 'class' => 'status-dikemas'],
                        'dikirim' => ['label' => 'Sedang Dikirim', 'class' => 'status-dikirim'],
                        'selesai' => ['label' => 'Selesai', 'class' => 'status-selesai'],
                        'batal' => ['label' => 'Dibatalkan', 'class' => 'status-batal'],
                    ];
                    $st = $statusMap[$order->status] ?? ['label' => $order->status, 'class' => 'status-menunggu'];
                @endphp
                <span class="status-badge {{ $st['class'] }}">{{ $st['label'] }}</span>

                @if ($order->payment)
                    <span style="margin-left: auto; font-size:12px; color:#64748b;">
                        Metode:
                        <strong>{{ strtoupper(str_replace('_', ' ', $order->payment->metode_pembayaran)) }}</strong>
                    </span>
                @endif
            </div>

            {{-- Body --}}
            <div class="invoice-body">

                {{-- Info pembeli & pengiriman --}}
                <div class="info-grid">
                    <div class="info-box">
                        <div class="info-box-title">Ditagih kepada</div>
                        <p>
                            <strong>{{ $order->user->name }}</strong>
                            {{ $order->user->email }}<br>
                            {{ $order->user->no_telp ?? '-' }}
                        </p>
                    </div>
                    <div class="info-box">
                        <div class="info-box-title">Alamat Pengiriman</div>
                        <p>
                            <strong>{{ $order->nama_penerima ?? $order->user->name }}</strong>
                            {{ $order->shipping_address ?? '-' }}<br>
                            {{ $order->no_telp_penerima ?? ($order->user->no_telp ?? '-') }}
                        </p>
                    </div>
                </div>

                {{-- Tabel produk --}}
                <div class="items-title">Daftar Produk</div>
                <table>
                    <thead>
                        <tr>
                            <th style="border-radius:8px 0 0 8px">Produk</th>
                            <th>Harga Satuan</th>
                            <th>Qty</th>
                            <th style="border-radius:0 8px 8px 0">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td>
                                    <div class="product-name">{{ $item->product->nama_produk ?? '-' }}</div>
                                    @if ($item->variant)
                                        <div class="product-variant">{{ $item->variant->nama_varian }}</div>
                                    @endif
                                </td>
                                <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td><span class="qty-badge">x{{ $item->jumlah }}</span></td>
                                <td>Rp {{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Totals --}}
                <div class="totals">
                    @php
                        $biayaAdmin = $order->total_harga - ($order->total_harga - $order->ongkir - 2500);
                        $subtotalProduk = $order->total_harga - $order->ongkir - 2500;
                    @endphp
                    <div class="total-row">
                        <span>Subtotal Produk</span>
                        <span>Rp {{ number_format($subtotalProduk, 0, ',', '.') }}</span>
                    </div>
                    <div class="total-row">
                        <span>Ongkos Kirim</span>
                        <span>Rp {{ number_format($order->ongkir, 0, ',', '.') }}</span>
                    </div>
                    <div class="total-row">
                        <span>Biaya Layanan</span>
                        <span>Rp 2.500</span>
                    </div>
                    <div class="total-row grand">
                        <span>Total</span>
                        <span class="amount">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Info kurir (jika sudah dikirim) --}}
                @if ($order->nama_kurir && $order->nomor_resi)
                    <div class="kurir-box">
                        <div class="kurir-icon">
                            <svg width="20" height="20" fill="none" stroke="#3b82f6" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>
                        <div class="kurir-info">
                            <strong>{{ $order->nama_kurir }}</strong>
                            <span>No. Resi: {{ $order->nomor_resi }}</span>
                        </div>
                    </div>
                @endif

            </div>

            {{-- Footer --}}
            <div class="invoice-footer">
                <div class="footer-note">
                    Terima kasih telah berbelanja di MampangPedia! 🙏<br>
                    Simpan invoice ini sebagai bukti transaksi kamu.
                </div>
                <div class="footer-brand">MampangPedia</div>
            </div>

        </div>
    </div>
</body>

</html>
