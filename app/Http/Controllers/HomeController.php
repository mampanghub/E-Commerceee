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

        $categories = Category::withCount('products')->limit(8)->get();

        return view('welcome', compact('products', 'categories'));
    }

    public function dashboard(Request $request)
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $validStatuses = ['dibayar', 'dikemas', 'dikirim', 'selesai'];
            $filterBulan   = $request->input('bulan');
            $filterTahun   = $request->input('tahun');

            $orderQuery = Order::whereIn('status', $validStatuses);

            if ($filterBulan) {
                [$thn, $bln] = explode('-', $filterBulan);
                $orderQuery->whereYear('created_at', $thn)->whereMonth('created_at', $bln);
            } elseif ($filterTahun) {
                $orderQuery->whereYear('created_at', $filterTahun);
            }

            $totalCuanAdmin   = (clone $orderQuery)->count() * 2500;
            $pendapatanProduk = (clone $orderQuery)->sum('total_harga');
            $totalOngkir      = (clone $orderQuery)->sum('ongkir');
            $totalPembeli     = User::where('role', 'pembeli')->whereNotNull('last_login_at')->count();

            $grafikData = collect();

            if ($filterBulan) {
                [$thn, $bln] = explode('-', $filterBulan);
                $jumlahHari  = \Carbon\Carbon::createFromDate($thn, $bln, 1)->daysInMonth;
                for ($d = 1; $d <= $jumlahHari; $d++) {
                    $tgl        = \Carbon\Carbon::createFromDate($thn, $bln, $d);
                    $pendapatan = Order::whereIn('status', $validStatuses)->whereDate('created_at', $tgl->toDateString())->sum('total_harga');
                    $adminFee   = Order::whereIn('status', $validStatuses)->whereDate('created_at', $tgl->toDateString())->count() * 2500;
                    $grafikData->push(['bulan' => $tgl->format('d'), 'pendapatan' => (int) $pendapatan, 'admin_fee' => (int) $adminFee]);
                }
            } elseif ($filterTahun) {
                for ($m = 1; $m <= 12; $m++) {
                    $bulan      = \Carbon\Carbon::createFromDate($filterTahun, $m, 1);
                    $pendapatan = Order::whereIn('status', $validStatuses)->whereYear('created_at', $filterTahun)->whereMonth('created_at', $m)->sum('total_harga');
                    $adminFee   = Order::whereIn('status', $validStatuses)->whereYear('created_at', $filterTahun)->whereMonth('created_at', $m)->count() * 2500;
                    $grafikData->push(['bulan' => $bulan->translatedFormat('M'), 'pendapatan' => (int) $pendapatan, 'admin_fee' => (int) $adminFee]);
                }
            } else {
                for ($i = 2; $i >= 0; $i--) {
                    $bulan      = now()->subMonths($i);
                    $pendapatan = Order::whereIn('status', $validStatuses)->whereYear('created_at', $bulan->year)->whereMonth('created_at', $bulan->month)->sum('total_harga');
                    $adminFee   = Order::whereIn('status', $validStatuses)->whereYear('created_at', $bulan->year)->whereMonth('created_at', $bulan->month)->count() * 2500;
                    $grafikData->push(['bulan' => $bulan->translatedFormat('M Y'), 'pendapatan' => (int) $pendapatan, 'admin_fee' => (int) $adminFee]);
                }
            }

            $tahunTersedia = Order::selectRaw('YEAR(created_at) as tahun')->groupBy('tahun')->orderByDesc('tahun')->pluck('tahun');

            $bulanTersedia = collect();
            foreach ($tahunTersedia as $thn) {
                $bulanDalamTahun = Order::whereYear('created_at', $thn)->selectRaw('MONTH(created_at) as bulan')->groupBy('bulan')->orderBy('bulan')->pluck('bulan');
                foreach ($bulanDalamTahun as $bln) {
                    $bulanTersedia->push([
                        'value' => $thn . '-' . str_pad($bln, 2, '0', STR_PAD_LEFT),
                        // DIUBAH DISINI: Hapus 'Y' agar hanya nama bulan yang muncul di label
                        'label' => \Carbon\Carbon::createFromDate($thn, $bln, 1)->translatedFormat('F'),
                    ]);
                }
            }

            return view('dashboard.admin', compact(
                'totalCuanAdmin',
                'pendapatanProduk',
                'totalOngkir',
                'totalPembeli',
                'grafikData',
                'tahunTersedia',
                'bulanTersedia',
                'filterBulan',
                'filterTahun',
            ));
        }

        if ($user->role === 'kurir') {
            return redirect()->route('kurir.index');
        }

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

        $products   = $query->paginate(12)->withQueryString();
        $categories = Category::all();
        $cartCount  = Cart::where('user_id', auth()->id())->count();

        $wishlistIds = \App\Models\Wishlist::where('user_id', auth()->id())
            ->pluck('product_id')
            ->toArray();

        return view('dashboard.pembeli', compact('products', 'categories', 'cartCount', 'wishlistIds'));
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

        $grafikData = collect();
        for ($i = 6; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $q     = Order::whereNotIn('status', ['batal', 'menunggu'])
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

        $produkTerlaris = \App\Models\OrderItem::with('product.primaryImage')
            ->whereHas('order', fn($q) => $q->whereNotIn('status', ['batal', 'menunggu']))
            ->when($request->filled('dari'), fn($q) => $q->whereHas('order', fn($o) => $o->whereDate('created_at', '>=', $request->dari)))
            ->when($request->filled('sampai'), fn($q) => $q->whereHas('order', fn($o) => $o->whereDate('created_at', '<=', $request->sampai)))
            ->selectRaw('product_id, SUM(jumlah) as total_terjual, SUM(jumlah * harga) as total_revenue')
            ->groupBy('product_id')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        $statusBreakdown = Order::whereNotIn('status', ['menunggu'])
            ->when($request->filled('dari'), fn($q) => $q->whereDate('created_at', '>=', $request->dari))
            ->when($request->filled('sampai'), fn($q) => $q->whereDate('created_at', '<=', $request->sampai))
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('dashboard.laporan', compact('orders', 'grafikData', 'produkTerlaris', 'statusBreakdown'));
    }
}
