<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KurirController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AddressController;
use Illuminate\Http\Request;

// ================= HALAMAN AWAL (SEMUA BISA AKSES) =================
Route::get('/', [HomeController::class, 'index'])->name('home');

// API WILAYAH (INDONESIA)
Route::get('/api/get-cities', function (Request $request) {
    return \Laravolt\Indonesia\Models\City::where('province_code', $request->province_id)->get();
})->name('api.cities');

Route::get('/api/get-districts', function (Request $request) {
    return \Laravolt\Indonesia\Models\District::where('city_code', $request->city_id)->get();
})->name('api.districts');

Route::get('/api/get-villages', function (Request $request) {
    return \Laravolt\Indonesia\Models\Village::where('district_code', $request->district_id)->get();
})->name('api.villages');

// ================= DASHBOARD =================
Route::get('/dashboard', [HomeController::class, 'dashboard'])
    ->middleware('auth')
    ->name('dashboard');

// ================= ROUTE KHUSUS ADMIN =================
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('categories', CategoryController::class);

    Route::resource('products', ProductController::class)->except(['show']);
    Route::post('/variants/{id}/add-stock', [ProductController::class, 'addStock'])->name('variants.add-stock');
    Route::post('/products/{id}/add-variant', [ProductController::class, 'storeVariant'])->name('variants.store');
    Route::get('/product-image/delete/{id}', [ProductController::class, 'deleteImage'])->name('product-image.destroy');
    Route::get('/products/{id}/stock-history', [ProductController::class, 'stockHistory'])
        ->name('products.stock-history');

    Route::post('/variants/{id}/reduce-stock', [ProductController::class, 'reduceStock'])
        ->name('variants.reduce-stock');

    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/laporan', [HomeController::class, 'laporan'])->name('admin.laporan');

    Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::patch('/orders/{id}/assign-kurir', [\App\Http\Controllers\KurirController::class, 'assign'])
        ->middleware(['auth', 'role:admin'])
        ->name('orders.assign-kurir');

    Route::get('/admin/users/create-kurir', [UserController::class, 'createKurir'])->name('admin.users.create-kurir');
    Route::post('/admin/users/store-kurir', [UserController::class, 'storeKurir'])->name('admin.users.store-kurir');
    Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
});

// ================= PEMBELI & UMUM (SUDAH LOGIN) =================
Route::middleware('auth')->group(function () {

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');

    Route::get('/orders/history', [OrderController::class, 'history'])->name('orders.history');
    Route::get('/orders/manage', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice'); // ← INVOICE
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::match(['get', 'post'], '/checkout/review', [OrderController::class, 'checkoutReview'])->name('checkout.review');
    Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{id}/confirm-delivery', [OrderController::class, 'confirmDelivery'])->name('orders.confirm-delivery');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::post('/products/{product_id}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review_id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::get('/profile/addresses', [AddressController::class, 'index'])->name('addresses.index');
    Route::post('/profile/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::patch('/profile/addresses/{id}', [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/profile/addresses/{id}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::patch('/profile/addresses/{id}/default', [AddressController::class, 'setDefault'])->name('addresses.set-default');
    Route::get('/api/addresses', [AddressController::class, 'list'])->name('addresses.list');
});

Route::middleware(['auth', 'role:kurir'])->prefix('kurir')->name('kurir.')->group(function () {
    Route::get('/', [KurirController::class, 'index'])->name('index');
    Route::get('/orders/{id}', [KurirController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{id}/status', [KurirController::class, 'updateStatus'])->name('orders.update-status');

    // ← BARU: Kurir ambil order sendiri (first-come-first-served)
    Route::post('/orders/{id}/take', [KurirController::class, 'takeOrder'])->name('orders.take');

    Route::get('/saldo', [KurirController::class, 'saldo'])->name('saldo');
    Route::post('/orders/{id}/foto', [KurirController::class, 'uploadFoto'])->name('orders.foto');
});
 

Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

require __DIR__ . '/auth.php';
