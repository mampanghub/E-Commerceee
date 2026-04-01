<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $order = Order::findOrFail($request->order_id);

        Payment::create([
            'order_id'      => $order->order_id,
            'metode'        => $request->metode,
            'status'        => 'dibayar',
            'tanggal_bayar' => now()
        ]);

        $order->update([
            'status' => 'dibayar'
        ]);

        return back()->with('success', 'Pembayaran berhasil');
    }

    public function confirmPayment(Request $request, $id)
{
    // 1. Cari data order (pastikan menggunakan kolom yang benar)
    $order = \App\Models\Order::where('order_id', $id)->firstOrFail();

    // 2. Konfigurasi Midtrans
    \Midtrans\Config::$serverKey = config('midtrans.server_key');
    \Midtrans\Config::$isProduction = config('midtrans.is_production', false);
    \Midtrans\Config::$isSanitized = true;
    \Midtrans\Config::$is3ds = true;

    // 3. Parameter (Tambahkan random string agar order_id unik di Midtrans)
    $params = [
        'transaction_details' => [
            // Gunakan suffix time() agar tidak error "Duplicated Order ID" di Midtrans
            'order_id' => $order->order_id . '-' . time(),
            'gross_amount' => (int)$order->total_harga,
        ],
        'customer_details' => [
            'first_name' => auth()->user()->name,
            'email' => auth()->user()->email,
        ],
    ];

    try {
        $snapToken = \Midtrans\Snap::getSnapToken($params);

        // 4. Update Database
        // Kita panggil save() secara eksplisit agar lebih aman
        $order->snap_token = $snapToken;
        $order->save();

        return redirect()->route('orders.show', $id)->with('success', 'Token berhasil dibuat, silakan bayar!');
    } catch (\Exception $e) {
        // Tampilkan error jika gagal konek ke Midtrans
        return redirect()->route('orders.show', $id)->with('error', 'Gagal: ' . $e->getMessage());
    }
}
}
