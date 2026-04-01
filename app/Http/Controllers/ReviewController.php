<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, $product_id)
    {
        $request->validate([
            'bintang'  => 'required|integer|min:1|max:5',
            'order_id' => 'required|exists:orders,order_id',
        ]);

        $product = Product::findOrFail($product_id);

        // Pastiin order ini milik user yang login & statusnya selesai/delivered
        $order = Order::where('order_id', $request->order_id)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['selesai', 'delivered', 'completed'])
            ->firstOrFail();

        // Pastiin produk ini memang ada di dalam order tersebut
        $orderHasProduct = $order->items()
            ->whereHas('variant.product', fn($q) => $q->where('product_id', $product_id))
            ->exists();

        if (!$orderHasProduct) {
            return back()->with('error', 'Kamu belum pernah membeli produk ini.');
        }

        // Cek sudah pernah review belum
        $sudahReview = Review::where('user_id', Auth::id())
            ->where('product_id', $product_id)
            ->where('order_id', $request->order_id)
            ->exists();

        if ($sudahReview) {
            return back()->with('error', 'Kamu sudah memberikan ulasan untuk produk ini.');
        }

        Review::create([
            'user_id'    => Auth::id(),
            'product_id' => $product_id,
            'order_id'   => $request->order_id,
            'bintang'    => $request->bintang,
            'komentar'   => $request->komentar,
        ]);

        return back()->with('success', 'Ulasan berhasil dikirim! Terima kasih.');
    }

    public function destroy($review_id)
    {
        $review = Review::where('review_id', $review_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $review->delete();

        return back()->with('success', 'Ulasan berhasil dihapus.');
    }
}