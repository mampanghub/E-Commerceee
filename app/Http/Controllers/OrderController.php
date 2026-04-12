<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ShippingOption;
use App\Services\ShippingService;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderController extends Controller
{
    protected ShippingService $shippingService;

    public function __construct(ShippingService $shippingService)
    {
        $this->shippingService = $shippingService;
    }

    public function checkoutReview(Request $request)
    {
        $user = Auth::user();

        // Ambil alamat terpilih
        $selectedAddress = $this->resolveAddress($request, $user);
        if (!$selectedAddress) {
            return redirect()->route('addresses.index')
                ->with('error', 'Tambahkan alamat pengiriman dulu sebelum checkout!');
        }

        $addresses = \App\Models\UserAddress::where('user_id', $user->user_id)
            ->with(['province', 'city', 'district', 'village'])->orderByDesc('is_default')->get();

        ['items' => $items, 'totalProduk' => $totalProduk, 'beratGram' => $beratGram, 'storeProvinceId' => $storeProvinceId]
            = $this->resolveItems($request, $user);

        $shippingData = $this->shippingService->getOpsiPengiriman(
            (string) $storeProvinceId,
            (string) $selectedAddress->province_id,
            $beratGram
        );

        $biayaAdmin = 2500;
        $zone = $shippingData['zone'];
        $opsiOngkir = $shippingData['options'];
        $selectedOptionId = $request->input('shipping_option_id', $opsiOngkir[0]['option_id']);
        $selectedOpsi = collect($opsiOngkir)->firstWhere('option_id', $selectedOptionId) ?? $opsiOngkir[0];
        $ongkir = $selectedOpsi['ongkir'];
        $totalAkhir = $totalProduk + $ongkir + $biayaAdmin;

        return view('checkout.review', compact(
            'items',
            'totalProduk',
            'ongkir',
            'biayaAdmin',
            'totalAkhir',
            'user',
            'request',
            'zone',
            'opsiOngkir',
            'selectedOptionId',
            'beratGram',
            'addresses',
            'selectedAddress',
        ));
    }

    // ─────────────────────────────────────────────
    // CHECKOUT — generate snap token + simpan ke cache
    // ─────────────────────────────────────────────
    public function checkout(Request $request)
    {
        $user = Auth::user();
        $biayaAdmin = 2500;

        $request->validate([
            'shipping_option_id' => 'required|exists:shipping_options,option_id',
        ]);

        $selectedAddress = $this->resolveAddress($request, $user);
        if (!$selectedAddress) {
            return redirect()->route('addresses.index')
                ->with('error', 'Tambahkan alamat pengiriman dulu sebelum checkout!');
        }

        try {
            [
                'items' => $items,
                'totalProduk' => $totalProduk,
                'beratGram' => $beratGram,
                'storeProvinceId' => $storeProvinceId,
                'itemsToProcess' => $itemsToProcess
            ]
                = $this->resolveItems($request, $user, withProcess: true);

            $shippingOption = ShippingOption::findOrFail($request->shipping_option_id);

            $zone = $this->shippingService->tentukanZona(
                (string) $storeProvinceId,
                (string) $selectedAddress->province_id
            );

            $ongkirDasar = $this->shippingService->hitungOngkirDasar(
                (string) $storeProvinceId,
                (string) $selectedAddress->province_id,
                $beratGram
            );
            $ongkir = $this->shippingService->hitungOngkirFinal($ongkirDasar, $shippingOption);
            $totalAkhir = $totalProduk + $ongkir + $biayaAdmin;

            [$estimasiMin, $estimasiMax] = app(\App\Services\ShippingService::class)
                ->hitungEstimasiRange($zone, $shippingOption);

            $estimasiTiba = now()->addDays($estimasiMin)->toDateString();
            $estimasiTibaMax = now()->addDays($estimasiMax)->toDateString();

            \Cache::put("pending_order_{$user->user_id}", [
                'user_id' => $user->user_id,
                'items' => $itemsToProcess,
                'ongkir' => $ongkir,
                'total_harga' => $totalAkhir,
                'zone_id' => $zone->zone_id,
                'shipping_option_id' => $shippingOption->option_id,
                'estimasi_hari' => $estimasiMin,
                'estimasi_tiba' => $estimasiTiba,
                'estimasi_tiba_max' => $estimasiTibaMax,
                'berat_total_gram' => $beratGram,
                'address_id' => $selectedAddress->address_id,
                'shipping_address' => $selectedAddress->alamat_lengkap,
                'nama_penerima' => $selectedAddress->nama_penerima,
                'no_telp_penerima' => $selectedAddress->no_telp,
                'catatan' => $request->input('catatan', ''),
                'from_cart' => !$request->has('product_id'),
            ], now()->addHours(2));

            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production', false);

            $tempOrderId = 'TMP-' . $user->user_id . '-' . time();
            $snapToken = Snap::getSnapToken([
                'transaction_details' => [
                    'order_id' => $tempOrderId,
                    'gross_amount' => (int) $totalAkhir,
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                ],
            ]);

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'snap_token' => $snapToken]);
            }

            // Re-fetch opsi untuk view
            $shippingData = $this->shippingService->getOpsiPengiriman(
                (string) $storeProvinceId,
                (string) $selectedAddress->province_id,  // ← benar
                $beratGram
            );
            $opsiOngkir = $shippingData['options'];
            $selectedOptionId = $shippingOption->option_id;

            $addresses = \App\Models\UserAddress::where('user_id', $user->user_id)
                ->with(['province', 'city', 'district', 'village'])
                ->orderByDesc('is_default')
                ->get();

            return view('checkout.review', compact(
                'items',
                'totalProduk',
                'ongkir',
                'biayaAdmin',
                'totalAkhir',
                'snapToken',
                'user',
                'request',
                'zone',
                'opsiOngkir',
                'selectedOptionId',
                'beratGram',
                'addresses',
                'selectedAddress',
            ));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    // ─────────────────────────────────────────────
    // MIDTRANS CALLBACK
    // ─────────────────────────────────────────────
    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        if (in_array($request->transaction_status, ['capture', 'settlement'])) {
            $parts = explode('-', $request->order_id); // TMP-{user_id}-{timestamp}
            $userId = $parts[1] ?? null;

            if (!$userId)
                return response()->json(['message' => 'Invalid order'], 400);

            $pending = \Cache::get("pending_order_{$userId}");
            if (!$pending)
                return response()->json(['message' => 'Pending order not found'], 404);

            DB::transaction(function () use ($request, $pending) {
                $order = Order::create([
                    'user_id' => $pending['user_id'],
                    'total_harga' => $pending['total_harga'],
                    'ongkir' => $pending['ongkir'],
                    'status' => 'dibayar',
                    'zone_id' => $pending['zone_id'],
                    'shipping_option_id' => $pending['shipping_option_id'],
                    'estimasi_hari' => $pending['estimasi_hari'],
                    'estimasi_tiba' => $pending['estimasi_tiba'],
                    'estimasi_tiba_max' => $pending['estimasi_tiba_max'] ?? $pending['estimasi_tiba'],
                    'berat_total_gram' => $pending['berat_total_gram'],
                    'shipping_address' => $pending['shipping_address'] ?? null,
                    'nama_penerima' => $pending['nama_penerima'] ?? null,
                    'no_telp_penerima' => $pending['no_telp_penerima'] ?? null,
                    'catatan' => $pending['catatan'] ?? null,
                ]);

                foreach ($pending['items'] as $item) {
                    OrderItem::create([
                        'order_id' => $order->order_id,
                        'product_id' => $item['product_id'],
                        'variant_id' => $item['variant_id'],
                        'jumlah' => $item['jumlah'],
                        'harga' => $item['harga'],
                    ]);

                    $model = $item['variant_id']
                        ? ProductVariant::find($item['variant_id'])
                        : Product::find($item['product_id']);
                    $stokLama = $model->stok;
                    $model->update(['stok' => $stokLama - $item['jumlah']]);
                    \App\Models\StockLog::create([
                        'variant_id' => $item['variant_id'],
                        'stok_lama' => $stokLama,
                        'stok_baru' => $stokLama - $item['jumlah'],
                        'jumlah' => $item['jumlah'],
                        'tipe' => 'keluar',
                        'keterangan' => 'Terjual - Order #' . $order->order_id,
                    ]);
                }

                \App\Models\Payment::create([
                    'order_id' => $order->order_id,
                    'metode_pembayaran' => $request->payment_type ?? 'unknown',
                    'status' => 'berhasil',
                ]);

                if ($pending['from_cart']) {
                    $cart = Cart::where('user_id', $pending['user_id'])->first();
                    $cart?->items()->delete();
                }

                $order->load(['user', 'items.product', 'items.variant', 'payment', 'shippingZone', 'shippingOption']);
                \Illuminate\Support\Facades\Mail::to($order->user->email)
                    ->queue(new \App\Mail\InvoiceMail($order));

                \Cache::forget("pending_order_{$pending['user_id']}");
            });
        }

        return response()->json(['message' => 'OK']);
    }

    // ─────────────────────────────────────────────
    // SHOW (admin + pembeli)
    // ─────────────────────────────────────────────
    public function show($id)
    {
        $order = Order::with([
            'user.province',
            'user.city',
            'user.district',
            'user.village',
            'items.product.primaryImage',
            'items.product.images',
            'items.variant',
            'shippingZone',
            'shippingOption',
            'kurir',
        ])->findOrFail($id);

        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production', false);

        if ($order->status == 'menunggu') {
            try {
                $statusMidtrans = \Midtrans\Transaction::status($order->order_id);
                $transaction = $statusMidtrans->transaction_status;
                if (in_array($transaction, ['settlement', 'capture'])) {
                    $order->update(['status' => 'dibayar']);
                } elseif (in_array($transaction, ['deny', 'expire', 'cancel'])) {
                    $order->update(['status' => 'batal']);
                }
                $order->refresh();
            } catch (\Exception $e) {
                logger("Cek status gagal: " . $e->getMessage());
            }
        }

        return view('orders.show', compact('order'));
    }

    // ─────────────────────────────────────────────
    // HISTORY (pembeli)
    // ─────────────────────────────────────────────
    public function history()
    {
        $tab = request('tab', 'dibayar');

        $query = Order::where('user_id', auth()->id())
            ->with(['items.product.primaryImage', 'items.variant', 'shippingOption'])
            ->whereNotIn('status', ['menunggu', 'batal'])
            ->orderBy('created_at', 'desc');

        if ($tab !== 'semua') {
            $query->where('status', $tab);
        }

        $orders = $query->paginate(10);

        $counts = Order::where('user_id', auth()->id())
            ->whereNotIn('status', ['menunggu', 'batal'])
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $counts = array_merge([
            'dibayar' => 0,
            'dikemas' => 0,
            'dikirim' => 0,
            'selesai' => 0,
            'batal' => 0,
        ], $counts);

        return view('orders.history', compact('orders', 'counts'));
    }

    // ─────────────────────────────────────────────
    // INDEX (admin)
    // ─────────────────────────────────────────────
    public function index()
    {
        if (auth()->user()->role !== 'admin')
            abort(403);

        $status = request('status', 'semua');

        $query = Order::with(['items.product.primaryImage', 'items.product.images', 'user', 'shippingOption'])
            ->orderBy('created_at', 'desc');

        if ($status !== 'semua') {
            $query->where('status', $status);
        }

        $orders = $query->paginate(10)->withQueryString();

        // Hitung count per status untuk badge tab
        $counts = Order::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
        $counts['semua'] = array_sum($counts);

        return view('orders.index', compact('orders', 'counts', 'status'));
    }

    // ─────────────────────────────────────────────
    // UPDATE STATUS (admin)
    // ─────────────────────────────────────────────
    public function updateStatus(Request $request, $id)
    {
        $order = Order::with('items.product', 'items.variant')->findOrFail($id);

        if ($request->status == 'dikirim') {
            $request->validate([
                'nama_kurir' => 'required|string|max:100',
                'nomor_resi' => 'required|string|max:100',
            ]);

            if ($order->status != 'dikemas') {
                return back()->with('error', 'Pembeli belum bayar, jangan dikirim dulu!');
            }
        }

        if ($request->status == 'batal' && $order->status != 'batal') {
            foreach ($order->items as $item) {
                $item->variant_id
                    ? $item->variant->increment('stok', $item->jumlah)
                    : $item->product->increment('stok', $item->jumlah);
            }
        }

        $order->update([
            'status' => $request->status,
            'nama_kurir' => $request->nama_kurir ?? $order->nama_kurir,
            'nomor_resi' => $request->nomor_resi ?? $order->nomor_resi,
        ]);

        return back()->with('success', 'Status berhasil diperbarui!');
    }

    // ─────────────────────────────────────────────
    // CANCEL (pembeli)
    // ─────────────────────────────────────────────
    public function cancel($id)
    {
        $order = Order::with('items.product', 'items.variant')
            ->where('order_id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'dibayar')
            ->firstOrFail();

        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                $model = $item->variant_id ? $item->variant : $item->product;
                $stokLama = $model->stok;
                $model->update(['stok' => $stokLama + $item->jumlah]);
                \App\Models\StockLog::create([
                    'variant_id' => $item->variant_id,
                    'stok_lama' => $stokLama,
                    'stok_baru' => $stokLama + $item->jumlah,
                    'jumlah' => $item->jumlah,
                    'tipe' => 'masuk',
                    'keterangan' => 'Batal - Order #' . $order->order_id,
                ]);
            }

            $refundAmount = $order->total_harga * 0.7;
            $order->user->increment('saldo', $refundAmount);
            $order->update(['status' => 'batal']);
        });

        return back()->with('success', 'Pesanan dibatalkan. Rp ' . number_format($order->total_harga * 0.7, 0, ',', '.') . ' (70%) dikembalikan ke saldo kamu.');
    }

    // ─────────────────────────────────────────────
    // CONFIRM DELIVERY (pembeli)
    // ─────────────────────────────────────────────
    public function confirmDelivery($id)
    {
        $order = Order::with('items')
            ->where('order_id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($order->status === 'dikirim') {
            DB::transaction(function () use ($order) {
                // Cegah double-increment kalau kurir juga konfirmasi
                $fresh = Order::where('order_id', $order->order_id)->lockForUpdate()->first();
                if ($fresh->status === 'selesai')
                    return;

                $order->update(['status' => 'selesai']);

                // Bayar ongkir ke kurir
                if ($order->kurir_id) {
                    \App\Models\User::where('user_id', $order->kurir_id)
                        ->increment('saldo', $order->ongkir);
                }
            });

            $firstItem = $order->items->first();
            return redirect()->route('orders.history')
                ->with('show_review_popup', true)
                ->with('review_order_id', $order->order_id)
                ->with('review_product_id', $firstItem->product_id);
        }

        return back()->with('error', 'Gagal konfirmasi.');
    }

    // ─────────────────────────────────────────────
    // INVOICE
    // ─────────────────────────────────────────────
    public function invoice($id)
    {
        $order = Order::with([
            'user.province',
            'user.city',
            'items.product',
            'items.variant',
            'payment',
            'shippingZone',
            'shippingOption',
        ])->findOrFail($id);

        if (auth()->user()->role !== 'admin' && $order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('orders.invoice', compact('order'));
    }

    // ─────────────────────────────────────────────
    // HELPER: resolve alamat yang dipakai checkout
    // ─────────────────────────────────────────────
    private function resolveAddress($request, $user): ?\App\Models\UserAddress
    {
        if ($request->filled('address_id')) {
            $addr = \App\Models\UserAddress::where('address_id', $request->address_id)
                ->where('user_id', $user->user_id)
                ->with(['province', 'city', 'district', 'village'])
                ->first();
            if ($addr)
                return $addr;
        }
        return \App\Models\UserAddress::where('user_id', $user->user_id)
            ->where('is_default', true)
            ->with(['province', 'city', 'district', 'village'])
            ->first();
    }

    // ─────────────────────────────────────────────
    // HELPER: resolve items dari request (cart / buy now)
    // ─────────────────────────────────────────────
    private function resolveItems(Request $request, $user, bool $withProcess = false): array
    {
        $items = [];
        $itemsToProcess = [];
        $totalProduk = 0;
        $beratGram = 0;
        $storeProvinceId = null;

        if ($request->has('product_id')) {
            $product = Product::with('store', 'images')->findOrFail($request->product_id);
            $qty = (int) $request->input('quantity', 1);
            $variantId = $request->input('variant_id');
            $variant = $variantId ? ProductVariant::findOrFail($variantId) : null;

            $hargaSatuan = $product->harga + ($variant->harga_tambahan ?? 0);

            if ($withProcess && ($variant ?? $product)->stok < $qty) {
                throw new Exception('Stok tidak mencukupi!');
            }

            $beratGram = ($variant->berat ?? $product->berat ?? 250) * $qty;
            $storeProvinceId = $product->store->province_id;
            $totalProduk = $hargaSatuan * $qty;

            $fotoVarian = $product->images->where('variant_id', $variantId)->first();
            $gambar = $fotoVarian?->gambar ?? $product->primaryImage?->gambar;

            $items[] = compact('product', 'variant', 'qty', 'hargaSatuan', 'gambar') + ['jumlah' => $qty, 'harga' => $hargaSatuan, 'subtotal' => $hargaSatuan * $qty];
            $itemsToProcess[] = ['product_id' => $product->product_id, 'variant_id' => $variantId, 'jumlah' => $qty, 'harga' => $hargaSatuan];
        } else {
            $cart = Cart::with('items.product.store', 'items.product.images', 'items.product.primaryImage', 'items.variant')
                ->where('user_id', $user->user_id)->first();

            if (!$cart || $cart->items->isEmpty()) {
                throw new Exception('Keranjang kosong!');
            }

            $selectedIds = $request->input('selected_items', []);
            $cartItems = $selectedIds ? $cart->items->whereIn('cart_item_id', $selectedIds) : $cart->items;

            if ($cartItems->isEmpty()) {
                throw new Exception('Pilih minimal satu barang dulu!');
            }

            $storeProvinceId = $cartItems->first()->product->store->province_id;

            foreach ($cartItems as $item) {
                $itemHarga = $item->product->harga + ($item->variant->harga_tambahan ?? 0);
                $totalProduk += $itemHarga * $item->jumlah;
                $beratGram += ($item->variant->berat ?? $item->product->berat ?? 250) * $item->jumlah;

                if ($withProcess && ($item->variant ?? $item->product)->stok < $item->jumlah) {
                    throw new Exception("Stok {$item->product->nama_produk} habis!");
                }

                $fotoVarian = $item->product->images->where('variant_id', $item->variant_id)->first();
                $gambar = $fotoVarian?->gambar ?? $item->product->primaryImage?->gambar;

                $items[] = ['product' => $item->product, 'variant' => $item->variant, 'jumlah' => $item->jumlah, 'harga' => $itemHarga, 'subtotal' => $itemHarga * $item->jumlah, 'gambar' => $gambar];
                $itemsToProcess[] = ['product_id' => $item->product_id, 'variant_id' => $item->variant_id, 'jumlah' => $item->jumlah, 'harga' => $itemHarga];
            }
        }

        return compact('items', 'itemsToProcess', 'totalProduk', 'beratGram', 'storeProvinceId');
    }

    public function invoicePrint($id)
    {
        $order = Order::with([
            'user',
            'items.product',
            'items.variant',
            'payment',
            'shippingZone',
            'shippingOption',
        ])->findOrFail($id);

        if (auth()->user()->role !== 'admin' && $order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('orders.invoice-print', compact('order'));
    }

    public function cetakResi($id)
    {
        $order = Order::with([
            'user',
            'items.product',
            'items.variant',
            'shippingZone',
            'shippingOption',
        ])->findOrFail($id);

        if (auth()->user()->role !== 'admin')
            abort(403);

        return view('orders.cetak-resi', compact('order'));
    }
}
