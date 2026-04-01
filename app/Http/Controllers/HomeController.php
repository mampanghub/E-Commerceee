<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Category;
use App\Models\Cart;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(['primaryImage', 'store', 'category', 'variants'])
            ->withAvg('reviews', 'bintang')
            ->withCount('reviews')
            ->withCount(['orderItems as terjual' => fn($q) => $q->whereHas('order', fn($o) => $o->whereIn('status', ['dikirim', 'selesai']))])
            ->latest()
            ->limit(10)
            ->get();

        $categories = Category::withCount('products')
            ->limit(8)
            ->get();

        return view('welcome', compact('products', 'categories'));
    }

    public function dashboard(Request $request)
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $validStatuses = ['dibayar', 'dikemas', 'dikirim', 'selesai'];

            $totalCuanAdmin   = Order::whereIn('status', $validStatuses)->count() * 2500;
            $pendapatanProduk = Order::whereIn('status', $validStatuses)->sum('total_harga');
            $totalOngkir      = Order::whereIn('status', $validStatuses)->sum('ongkir');
            $totalPembeli     = User::where('role', 'pembeli')
                ->whereNotNull('last_login_at')
                ->count();

            $grafikData = collect();
            for ($i = 6; $i >= 0; $i--) {
                $bulan = now()->subMonths($i);
                $pendapatan = Order::whereIn('status', $validStatuses)
                    ->whereYear('created_at', $bulan->year)
                    ->whereMonth('created_at', $bulan->month)
                    ->sum('total_harga');

                $adminFee = Order::whereIn('status', $validStatuses)
                    ->whereYear('created_at', $bulan->year)
                    ->whereMonth('created_at', $bulan->month)
                    ->count() * 2500;

                $grafikData->push([
                    'bulan'      => $bulan->translatedFormat('M Y'),
                    'pendapatan' => (int) $pendapatan,
                    'admin_fee'  => (int) $adminFee,
                ]);
            }

            $orderTerbaru = Order::with(['user', 'items.product'])
                ->whereIn('status', $validStatuses)
                ->latest()
                ->take(5)
                ->get();

            return view('dashboard.admin', compact(
                'totalCuanAdmin',
                'pendapatanProduk',
                'totalOngkir',
                'totalPembeli',
                'grafikData',
                'orderTerbaru',
            ));
        }

        if ($user->role === 'kurir') {
            return redirect()->route('kurir.index');
        }

        // PEMBELI — tambah withAvg & withCount
        $query = Product::with(['primaryImage', 'category', 'store'])
            ->withAvg('reviews', 'bintang')
            ->withCount('reviews')
            ->withCount(['orderItems as terjual' => fn($q) => $q->whereHas('order', fn($o) => $o->whereIn('status', ['dikirim', 'selesai']))]);

        if ($request->has('search')) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        if ($request->sort == 'termurah') {
            $query->orderBy('harga', 'asc');
        } elseif ($request->sort == 'termahal') {
            $query->orderBy('harga', 'desc');
        } else {
            $query->latest();
        }

        $products   = $query->get();
        $categories = Category::all();
        $cartCount  = Cart::where('user_id', auth()->id())->count();

        return view('dashboard.pembeli', compact('products', 'categories', 'cartCount'));
    }

    public function laporan(Request $request)
    {
        $query = Order::with(['user', 'items.product.store'])
            ->whereNotIn('status', ['batal', 'menunggu'])
            ->latest();

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereDate('created_at', '>=', $request->dari)
                ->whereDate('created_at', '<=', $request->sampai);
        }

        $orders = $query->get();

        // Grafik 7 bulan terakhir
        $grafikData = collect();
        for ($i = 6; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $q = Order::whereNotIn('status', ['batal', 'menunggu'])
                ->whereYear('created_at', $bulan->year)
                ->whereMonth('created_at', $bulan->month);

            if ($request->filled('dari') && $request->filled('sampai')) {
                $q->whereDate('created_at', '>=', $request->dari)
                    ->whereDate('created_at', '<=', $request->sampai);
            }

            $grafikData->push([
                'bulan'      => $bulan->translatedFormat('M Y'),
                'pendapatan' => (int) (clone $q)->sum('total_harga'),
                'jumlah'     => (int) (clone $q)->count(),
            ]);
        }

        // Produk terlaris
        $produkTerlaris = \App\Models\OrderItem::with('product.primaryImage')
            ->whereHas('order', fn($q) => $q->whereNotIn('status', ['batal', 'menunggu']))
            ->when($request->filled('dari'), fn($q) => $q->whereHas('order', fn($o) => $o->whereDate('created_at', '>=', $request->dari)))
            ->when($request->filled('sampai'), fn($q) => $q->whereHas('order', fn($o) => $o->whereDate('created_at', '<=', $request->sampai)))
            ->selectRaw('product_id, SUM(jumlah) as total_terjual, SUM(jumlah * harga) as total_revenue')
            ->groupBy('product_id')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        // Breakdown status
        $statusBreakdown = Order::whereNotIn('status', ['menunggu'])
            ->when($request->filled('dari'), fn($q) => $q->whereDate('created_at', '>=', $request->dari))
            ->when($request->filled('sampai'), fn($q) => $q->whereDate('created_at', '<=', $request->sampai))
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('dashboard.laporan', compact('orders', 'grafikData', 'produkTerlaris', 'statusBreakdown'));
    }
}
