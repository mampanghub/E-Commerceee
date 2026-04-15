<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = auth()->user()->wishlists()
            ->with(['product.primaryImage', 'product.images', 'product.store', 'product.variants'])
            ->latest()
            ->get();

        return view('wishlist.index', compact('wishlists'));
    }

    public function toggle(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,product_id']);

        $user = auth()->user();
        $productId = $request->product_id;

        $existing = Wishlist::where('user_id', $user->user_id)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            $existing->delete();
            $status = 'removed';
            $wishlisted = false;
        } else {
            Wishlist::create([
                'user_id'    => $user->user_id,
                'product_id' => $productId,
            ]);
            $status = 'added';
            $wishlisted = true;
        }

        if ($request->expectsJson()) {
            return response()->json([
                'status' => $status,           // Untuk dashboard
                'wishlisted' => $wishlisted,   // Untuk product detail
                'count' => $user->wishlists()->count(),
            ]);
        }

        return back()->with('success', $wishlisted ? 'Ditambahkan ke wishlist.' : 'Dihapus dari wishlist.');
    }

    public function remove($id)
    {
        $deleted = Wishlist::where('wishlist_id', $id)
            ->where('user_id', auth()->user()->user_id)
            ->delete();

        if ($deleted > 0) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }
}
