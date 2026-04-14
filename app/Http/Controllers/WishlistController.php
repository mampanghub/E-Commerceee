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
            $wishlisted = false;
        } else {
            Wishlist::create([
                'user_id'    => $user->user_id,
                'product_id' => $productId,
            ]);
            $wishlisted = true;
        }

        if ($request->expectsJson()) {
            return response()->json([
                'wishlisted' => $wishlisted,
                'count'      => $user->wishlists()->count(),
            ]);
        }

        return back()->with('success', $wishlisted ? 'Ditambahkan ke wishlist.' : 'Dihapus dari wishlist.');
    }

    public function remove($id)
    {
        Wishlist::where('wishlist_id', $id)
            ->where('user_id', auth()->user()->user_id)
            ->delete();

        return back()->with('success', 'Dihapus dari wishlist.');
    }
}
