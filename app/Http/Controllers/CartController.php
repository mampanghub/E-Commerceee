<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::where('user_id', auth()->id())->first();
        $items = $cart ? $cart->items()->with(['product.primaryImage', 'product.images', 'variant'])->get() : collect();

        $total = $items->sum(function ($item) {
            $hargaDasar = $item->product->harga;
            $hargaTambahan = $item->variant ? $item->variant->harga_tambahan : 0;
            return ($hargaDasar + $hargaTambahan) * $item->jumlah;
        });

        return response()
            ->view('cart.index', compact('items', 'total'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function add(Request $request)
    {
        $cart = Cart::firstOrCreate([
            'user_id' => Auth::id()
        ]);

        $product = Product::findOrFail($request->product_id);

        $variantId = $request->variant_id;

        $cartItem = CartItem::where('cart_id', $cart->cart_id)
            ->where('product_id', $product->product_id)
            ->where('variant_id', $variantId)
            ->first();

        // SESUDAH
        if ($cartItem) {
            $cartItem->update(['jumlah' => $cartItem->jumlah + ($request->quantity ?? 1)]);
        } else {
            CartItem::create([
                'cart_id'    => $cart->cart_id,
                'product_id' => $product->product_id,
                'variant_id' => $variantId,
                'jumlah'     => $request->quantity ?? 1
            ]);
        }

        if ($request->wantsJson()) {
            $cartCount = CartItem::whereHas('cart', fn($q) => $q->where('user_id', Auth::id()))->sum('jumlah');

            return response()->json([
                'success'    => true,
                'message'    => 'Produk berhasil ditambahkan ke keranjang.',
                'cart_count' => $cartCount,
            ]);
        }

        return back()->with('success', 'Produk masuk keranjang');
    }

    public function remove($id)
    {
        // FIX: cek kepemilikan sebelum hapus — user lain tidak bisa hapus cart orang lain
        $cartItem = CartItem::where('cart_item_id', $id)
            ->whereHas('cart', fn($q) => $q->where('user_id', Auth::id()))
            ->firstOrFail();

        $cartItem->delete();

        return back()->with('success', 'Produk dihapus dari keranjang');
    }

    public function update(Request $request, $id)
    {
        // FIX: cek kepemilikan sebelum update
        $cartItem = CartItem::where('cart_item_id', $id)
            ->whereHas('cart', fn($q) => $q->where('user_id', Auth::id()))
            ->firstOrFail();

        $request->validate([
            'jumlah' => 'required|integer|min:1'
        ]);

        $stokMaksimal = $cartItem->variant ? $cartItem->variant->stok : $cartItem->product->stok;

        if ($request->jumlah > $stokMaksimal) {
            return response()->json(['message' => 'Stok tidak cukup, maksimal ' . $stokMaksimal], 422);
        }

        $cartItem->update(['jumlah' => $request->jumlah]);

        return response()->json(['success' => true, 'jumlah_tersimpan' => $cartItem->fresh()->jumlah]);
    }
}
