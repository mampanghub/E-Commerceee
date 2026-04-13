<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KurirController extends Controller
{
    public function index()
    {
        $tab = request('tab', 'tersedia');

        $aktif = Order::where('kurir_id', Auth::id())
            ->whereIn('status', ['dikemas', 'dikirim'])
            ->with(['items.product.primaryImage', 'items.product.images', 'items.variant', 'shippingOption'])
            ->orderByRaw("FIELD(status, 'dikemas', 'dikirim')")
            ->orderBy('updated_at', 'asc')
            ->get();

        $tersedia = Order::where('status', 'dikemas')
            ->whereNull('kurir_id')
            ->with(['items.product.primaryImage', 'items.product.images', 'items.variant', 'shippingOption'])
            ->orderBy('updated_at', 'asc')
            ->get();

        $history = Order::where('kurir_id', Auth::id())
            ->where('status', 'selesai')
            ->with(['user', 'items.product.primaryImage', 'shippingOption'])
            ->orderBy('updated_at', 'desc')
            ->paginate(10, ['*'], 'page');

        return view('kurir.index', compact('aktif', 'tersedia', 'history', 'tab'));
    }

    public function show($id)
    {
        $order = Order::where('order_id', $id)
            ->where(function ($q) {
                $q->where('kurir_id', Auth::id())
                    ->orWhere(function ($q2) {
                        $q2->where('status', 'dikemas')->whereNull('kurir_id');
                    });
            })
            ->with([
                'user.province', 'user.city', 'user.district', 'user.village',
                'items.product.primaryImage', 'items.product.images',
                'items.variant', 'shippingOption',
            ])
            ->firstOrFail();

        return view('kurir.show', compact('order'));
    }

    public function takeOrder($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $order = Order::where('order_id', $id)
                    ->where('status', 'dikemas')
                    ->whereNull('kurir_id')
                    ->lockForUpdate()
                    ->firstOrFail();

                $resiPrefix = strtoupper(substr(Auth::user()->name, 0, 3));
                $nomorResi  = $resiPrefix . Auth::id() . $order->order_id . time();

                $order->update([
                    'kurir_id'   => Auth::id(),
                    'nama_kurir' => Auth::user()->name,
                    'status'     => 'dikirim',
                    'nomor_resi' => $nomorResi,
                    'dikirim_at' => now(),
                ]);
            });

            return redirect()->route('kurir.index', ['tab' => 'aktif'])
                ->with('success', "Paket #$id berhasil diambil! Resi sudah digenerate, segera antar ya.");
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('kurir.index', ['tab' => 'tersedia'])
                ->with('error', "Paket #$id sudah diambil kurir lain. Cari paket lainnya!");
        }
    }

    public function updateStatus(Request $request, $id)
    {
        // FIX: pastikan order ini memang milik kurir yang sedang login
        $order = Order::where('order_id', $id)
            ->where('kurir_id', Auth::id())
            ->firstOrFail();

        if ($request->status === 'dikirim') {
            if ($order->status !== 'dikemas') {
                return back()->with('error', 'Order belum dikemas, tidak bisa dikirim!');
            }
            $resiPrefix = strtoupper(substr(Auth::user()->name, 0, 3));
            $nomorResi  = $resiPrefix . Auth::id() . $order->order_id . time();
            $order->update([
                'status'     => 'dikirim',
                'nomor_resi' => $nomorResi,
                'nama_kurir' => Auth::user()->name,
                'dikirim_at' => now(),
            ]);
            return back()->with('success', "Paket #{$order->order_id} sedang dikirim! Resi: {$nomorResi}");
        }

        if ($request->status === 'selesai') {
            if ($order->status !== 'dikirim') {
                return back()->with('error', 'Order belum dalam status dikirim!');
            }

            $request->validate([
                'foto_konfirmasi' => 'required|image|mimes:jpg,jpeg,png|max:3072',
            ], [
                'foto_konfirmasi.required' => 'Foto konfirmasi wajib diupload.',
                'foto_konfirmasi.max'      => 'Ukuran foto maksimal 3MB.',
            ]);

            // FIX: simpan foto dengan path yang menyertakan order_id dan kurir_id
            // supaya tidak bisa pakai foto lama dari order lain
            $fotoPath = $request->file('foto_konfirmasi')
                ->storeAs(
                    'konfirmasi-pengiriman',
                    "order-{$order->order_id}-kurir-" . Auth::id() . '-' . time() . '.' . $request->file('foto_konfirmasi')->extension(),
                    'public'
                );

            DB::transaction(function () use ($order, $fotoPath) {
                $fresh = Order::where('order_id', $order->order_id)->lockForUpdate()->first();

                // FIX: validasi ulang bahwa order ini masih milik kurir yang sama
                // dan belum selesai — cegah race condition atau manipulasi
                if ($fresh->status === 'selesai') return;
                if ($fresh->kurir_id !== Auth::id()) {
                    throw new \Exception('Order ini bukan milik kamu.');
                }

                $fresh->update([
                    'status'           => 'selesai',
                    'foto_konfirmasi'  => $fotoPath,
                ]);

                Auth::user()->increment('saldo', $order->ongkir);
            });

            return back()->with('success', "Order #{$order->order_id} selesai! Ongkir Rp " . number_format($order->ongkir, 0, ',', '.') . " masuk ke saldo kamu.");
        }

        return back()->with('error', 'Status tidak valid.');
    }

    public function saldo()
    {
        $kurir = Auth::user();
        $riwayat = Order::where('kurir_id', $kurir->user_id)
            ->where('status', 'selesai')
            ->with(['user', 'shippingOption'])
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        $totalPenghasilan = Order::where('kurir_id', $kurir->user_id)
            ->where('status', 'selesai')
            ->sum('ongkir');

        return view('kurir.saldo', compact('kurir', 'riwayat', 'totalPenghasilan'));
    }

    public function assign(Request $request, $id)
    {
        $request->validate(['kurir_id' => 'required|exists:users,user_id']);
        $kurir = User::where('user_id', $request->kurir_id)->where('role', 'kurir')->firstOrFail();
        $order = Order::where('order_id', $id)->where('status', 'dikemas')->firstOrFail();
        $order->update([
            'kurir_id'   => $kurir->user_id,
            'nama_kurir' => $kurir->name,
        ]);
        return back()->with('success', "Kurir {$kurir->name} berhasil di-assign ke Order #{$id}!");
    }
}
