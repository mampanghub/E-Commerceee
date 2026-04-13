<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        // FIX: pastikan order milik user yang sedang login
        $order = Order::where('order_id', $request->order_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // FIX: nama kolom disamain dengan Payment model ('metode_pembayaran')
        Payment::create([
            'order_id'           => $order->order_id,
            'metode_pembayaran'  => $request->metode,
            'status'             => 'dibayar',
            'tanggal_bayar'      => now(),
        ]);

        $order->update([
            'status' => 'dibayar'
        ]);

        return back()->with('success', 'Pembayaran berhasil');
    }

    public function confirmPayment(Request $request, $id)
    {
        // FIX: pastikan order milik user yang sedang login
        $order = Order::where('order_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        \Midtrans\Config::$serverKey    = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production', false);
        \Midtrans\Config::$isSanitized  = true;
        \Midtrans\Config::$is3ds        = true;

        $params = [
            'transaction_details' => [
                'order_id'     => $order->order_id . '-' . time(),
                'gross_amount' => (int) $order->total_harga,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email'      => Auth::user()->email,
            ],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            $order->snap_token = $snapToken;
            $order->save();

            return redirect()->route('orders.show', $id)
                ->with('success', 'Token berhasil dibuat, silakan bayar!');
        } catch (\Exception $e) {
            return redirect()->route('orders.show', $id)
                ->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
}
