<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\StockLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'primaryImage'])
            ->latest()
            ->get();

        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id'            => 'required',
            'nama_produk'            => 'required',
            'harga'                  => 'required|numeric',
            'variants'               => 'required|array|min:1',
            'variants.*.nama_varian' => 'required|string',
            'variants.*.stok'        => 'required|numeric|min:0',
            'variants.*.berat'       => 'required|numeric|min:1',
            'images.*'               => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $product = Product::create([
                    'category_id' => $request->category_id,
                    'store_id'    => 1,
                    'nama_produk' => $request->nama_produk,
                    'harga'       => $request->harga,
                    'deskripsi'   => $request->deskripsi,
                    'spesifikasi' => $request->spesifikasi,
                ]);

                $variantMap = [];
                foreach ($request->variants as $v) {
                    $newVariant = $product->variants()->create([
                        'nama_varian'    => $v['nama_varian'],
                        'stok'           => $v['stok'],
                        'berat'          => $v['berat'],
                        'harga_tambahan' => $v['harga_tambahan'] ?? 0,
                    ]);

                    StockLog::create([
                        'variant_id' => $newVariant->variant_id,
                        'stok_lama'  => 0,
                        'stok_baru'  => $v['stok'],
                        'jumlah'     => $v['stok'],
                        'tipe'       => 'masuk',
                        'keterangan' => 'Stok awal produk baru',
                    ]);

                    $variantMap[$v['nama_varian']] = $newVariant->variant_id;
                }

                if ($request->hasFile('images')) {
                    $primaryIndexes = array_map('strval', $request->input('is_primary_idx', []));
                    $autoPrimary    = empty($primaryIndexes);

                    foreach ($request->file('images') as $key => $img) {
                        $path        = $img->store('products', 'public');
                        $taggedNames = $request->input("image_tags.{$key}", []);
                        $isPrimary   = $autoPrimary
                            ? ($key === 0 ? 1 : 0)
                            : (in_array((string) $key, $primaryIndexes) ? 1 : 0);

                        if (empty($taggedNames)) {
                            ProductImage::create([
                                'product_id' => $product->product_id,
                                'variant_id' => null,
                                'gambar'     => $path,
                                'is_primary' => $isPrimary,
                            ]);
                        } else {
                            foreach ($taggedNames as $tagName) {
                                ProductImage::create([
                                    'product_id' => $product->product_id,
                                    'variant_id' => $variantMap[$tagName] ?? null,
                                    'gambar'     => $path,
                                    'is_primary' => $isPrimary,
                                ]);
                                $isPrimary = 0;
                            }
                        }
                    }
                }
            });

            return redirect()->route('products.index')->with('success', 'Produk tersimpan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $product    = Product::with('images', 'variants')->findOrFail($id);
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id'            => 'required',
            'nama_produk'            => 'required',
            'harga'                  => 'required|numeric',
            'variants'               => 'required|array',
            'variants.*.nama_varian' => 'required',
            'variants.*.stok'        => 'required|numeric',
            'variants.*.berat'       => 'required|numeric|min:1',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $product = Product::findOrFail($id);

                $product->update([
                    'category_id' => $request->category_id,
                    'nama_produk' => $request->nama_produk,
                    'harga'       => $request->harga,
                    'deskripsi'   => $request->deskripsi,
                ]);

                // FIX: upsert per variant_id, bukan delete semua
                // Kumpulkan variant_id yang dikirim dari form (yang sudah ada)
                $existingIds = [];

                foreach ($request->variants as $v) {
                    $variantId = $v['variant_id'] ?? null;

                    if ($variantId) {
                        // Update varian yang sudah ada
                        $variant  = ProductVariant::where('variant_id', $variantId)
                            ->where('product_id', $id)
                            ->firstOrFail();

                        $stokLama = $variant->stok;
                        $stokBaru = (int) $v['stok'];

                        $variant->update([
                            'nama_varian'    => $v['nama_varian'],
                            'stok'           => $stokBaru,
                            'berat'          => $v['berat'],
                            'harga_tambahan' => $v['harga_tambahan'] ?? 0,
                        ]);

                        // Catat perubahan stok kalau ada selisih
                        if ($stokLama !== $stokBaru) {
                            $selisih = abs($stokBaru - $stokLama);
                            StockLog::create([
                                'variant_id' => $variant->variant_id,
                                'stok_lama'  => $stokLama,
                                'stok_baru'  => $stokBaru,
                                'jumlah'     => $selisih,
                                'tipe'       => $stokBaru > $stokLama ? 'masuk' : 'keluar',
                                'keterangan' => 'Edit produk - update stok manual',
                            ]);
                        }

                        $existingIds[] = $variant->variant_id;
                    } else {
                        // Varian baru — create + log stok awal
                        $newVariant = $product->variants()->create([
                            'nama_varian'    => $v['nama_varian'],
                            'stok'           => $v['stok'],
                            'berat'          => $v['berat'],
                            'harga_tambahan' => $v['harga_tambahan'] ?? 0,
                        ]);

                        StockLog::create([
                            'variant_id' => $newVariant->variant_id,
                            'stok_lama'  => 0,
                            'stok_baru'  => $v['stok'],
                            'jumlah'     => $v['stok'],
                            'tipe'       => 'masuk',
                            'keterangan' => 'Varian baru ditambahkan via edit produk',
                        ]);

                        $existingIds[] = $newVariant->variant_id;
                    }
                }

                // Hapus varian yang dihilangkan dari form (tidak ada di existingIds)
                $product->variants()
                    ->whereNotIn('variant_id', $existingIds)
                    ->delete();

                // Handle gambar baru kalau ada
                if ($request->hasFile('images')) {
                    $hasPrimary = ProductImage::where('product_id', $id)->where('is_primary', 1)->exists();
                    foreach ($request->file('images') as $img) {
                        $path = $img->store('products', 'public');
                        ProductImage::create([
                            'product_id' => $product->product_id,
                            'gambar'     => $path,
                            'is_primary' => !$hasPrimary ? 1 : 0,
                        ]);
                        $hasPrimary = true;
                    }
                }
            });

            return redirect()->route('products.index')->with('success', 'Produk berhasil diupdate');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $product = Product::with('images')->findOrFail($id);
        foreach ($product->images as $img) {
            Storage::disk('public')->delete($img->gambar);
        }
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus');
    }

    public function deleteImage($id)
    {
        $image = ProductImage::findOrFail($id);
        if (Storage::disk('public')->exists($image->gambar)) {
            Storage::disk('public')->delete($image->gambar);
        }
        $image->delete();
        return back()->with('success', 'Gambar berhasil dihapus!');
    }

    public function show($id)
    {
        $product = Product::with(['store', 'category', 'images', 'variants'])->findOrFail($id);

        $reviews = \App\Models\Review::with(['user', 'variant'])
            ->where('product_id', $id)
            ->latest()
            ->get();

        $eligibleOrders = collect();
        if (auth()->check() && auth()->user()->role === 'pembeli') {
            $eligibleOrders = \App\Models\Order::where('user_id', auth()->id())
                ->whereIn('status', ['selesai', 'delivered', 'completed'])
                ->whereHas('items', fn($q) => $q->where('product_id', $id))
                ->with(['items' => fn($q) => $q->where('product_id', $id)->with('variant')])
                ->get();
        }

        return view('products.show', compact('product', 'reviews', 'eligibleOrders'));
    }

    public function addStock(Request $request, $id)
    {
        $variant = ProductVariant::findOrFail($id);

        $request->validate([
            'jumlah_masuk' => 'required|numeric|min:1',
        ]);

        $stokLama = $variant->stok;
        $stokBaru = $stokLama + $request->jumlah_masuk;

        $variant->update(['stok' => $stokBaru]);

        StockLog::create([
            'variant_id' => $variant->variant_id,
            'stok_lama'  => $stokLama,
            'stok_baru'  => $stokBaru,
            'jumlah'     => $request->jumlah_masuk,
            'tipe'       => 'masuk',
            'keterangan' => 'Restock manual',
        ]);

        return back()->with('success', 'Stok ' . $variant->nama_varian . ' berhasil ditambah dari ' . $stokLama . ' → ' . $stokBaru . '!');
    }

    public function storeVariant(Request $request, $product_id)
    {
        $request->validate([
            'nama_varian' => 'required',
            'harga'       => 'required|numeric',
            'stok_awal'   => 'required|numeric',
            'berat'       => 'required|numeric',
            'gambar'      => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $product = Product::findOrFail($product_id);

        $variant = ProductVariant::create([
            'product_id'     => $product_id,
            'nama_varian'    => $request->nama_varian,
            'harga_tambahan' => $request->harga - $product->harga,
            'stok'           => $request->stok_awal,
            'berat'          => $request->berat,
        ]);

        StockLog::create([
            'variant_id' => $variant->variant_id,
            'stok_lama'  => 0,
            'stok_baru'  => $request->stok_awal,
            'jumlah'     => $request->stok_awal,
            'tipe'       => 'masuk',
            'keterangan' => 'Stok awal varian baru',
        ]);

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('products', 'public');
            ProductImage::create([
                'product_id' => $product_id,
                'variant_id' => $variant->variant_id,
                'gambar'     => $path,
                'is_primary' => 0,
            ]);
        }

        return back()->with('success', 'Varian ' . $request->nama_varian . ' berhasil ditambahkan!');
    }

    public function stockHistory($id)
    {
        $product = Product::with('variants.stockLogs')->findOrFail($id);

        $logs = StockLog::whereIn('variant_id', $product->variants->pluck('variant_id'))
            ->with('variant')
            ->latest()
            ->paginate(20);

        return view('products.stock-history', compact('product', 'logs'));
    }

    public function reduceStock(Request $request, $id)
    {
        $variant = ProductVariant::findOrFail($id);

        $request->validate([
            'jumlah_kurang' => ['required', 'numeric', 'min:1', 'max:' . $variant->stok],
        ], [
            'jumlah_kurang.max' => 'Stok tidak cukup! Stok tersedia hanya ' . $variant->stok . ' pcs.',
        ]);

        $stokLama = $variant->stok;
        $stokBaru = $stokLama - $request->jumlah_kurang;

        $variant->update(['stok' => $stokBaru]);

        StockLog::create([
            'variant_id' => $variant->variant_id,
            'stok_lama'  => $stokLama,
            'stok_baru'  => $stokBaru,
            'jumlah'     => $request->jumlah_kurang,
            'tipe'       => 'keluar',
            'keterangan' => 'Pengurangan manual',
        ]);

        return back()->with('success', 'Stok ' . $variant->nama_varian . ' berkurang dari ' . $stokLama . ' → ' . $stokBaru . '!');
    }

    public function suggestions(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 1) {
            return response()->json([]);
        }

        $products = Product::with('primaryImage')
            ->where('nama_produk', 'LIKE', "%{$query}%")
            ->select('product_id', 'nama_produk', 'harga')
            ->limit(6)
            ->get()
            ->map(fn($p) => [
                'product_id'  => $p->product_id,
                'nama_produk' => $p->nama_produk,
                'harga'       => $p->harga,
                'foto_produk' => $p->primaryImage?->gambar,
            ]);

        return response()->json($products);
    }
}
