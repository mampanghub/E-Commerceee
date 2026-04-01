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

        if ($cartItem) {
            $cartItem->update(['jumlah' => $cartItem->jumlah + 1]);
        } else {
            CartItem::create([
                'cart_id'    => $cart->cart_id,
                'product_id' => $product->product_id,
                'variant_id' => $variantId, // SIMPAN variant_id di sini
                'jumlah'     => 1
            ]);
        }

        return back()->with('success', 'Produk masuk keranjang');
    }

    public function remove($id)
    {
        CartItem::destroy($id);
        return back()->with('success', 'Produk dihapus dari keranjang');
    }

    public function checkout(Request $request)
    {
        // Method ini tidak digunakan lagi karena checkout via AJAX
        // Bisa dihapus atau dibiarkan sebagai fallback
        return back()->with('error', 'Gunakan checkout via AJAX');
    }

    public function update(Request $request, $id)
    {
        $cartItem = CartItem::findOrFail($id);

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
