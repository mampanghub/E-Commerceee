<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
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
use App\Http\Controllers\ShippingSettingController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\WishlistController;
use Illuminate\Http\Request;

// FIX: rate limiter untuk checkout (5x/menit) dan payment (10x/menit)
RateLimiter::for('checkout', function (Request $request) {
    return Limit::perMinute(5)->by($request->user()?->user_id ?: $request->ip());
});

RateLimiter::for('payment', function (Request $request) {
    return Limit::perMinute(10)->by($request->user()?->user_id ?: $request->ip());
});

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/api/get-cities', function (Request $request) {
    return \Laravolt\Indonesia\Models\City::where('province_code', $request->province_id)->get();
})->name('api.cities');

Route::get('/api/get-districts', function (Request $request) {
    return \Laravolt\Indonesia\Models\District::where('city_code', $request->city_id)->get();
})->name('api.districts');

Route::get('/api/get-villages', function (Request $request) {
    return \Laravolt\Indonesia\Models\Village::where('district_code', $request->district_id)->get();
})->name('api.villages');

Route::get('/dashboard', [HomeController::class, 'dashboard'])
    ->middleware('auth')
    ->name('dashboard');

// ===== ADMIN =====
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class)->except(['show']);
    Route::post('/variants/{id}/add-stock', [ProductController::class, 'addStock'])->name('variants.add-stock');
    Route::post('/products/{id}/add-variant', [ProductController::class, 'storeVariant'])->name('variants.store');
    Route::get('/product-image/delete/{id}', [ProductController::class, 'deleteImage'])->name('product-image.destroy');
    Route::get('/products/{id}/stock-history', [ProductController::class, 'stockHistory'])->name('products.stock-history');
    Route::post('/variants/{id}/reduce-stock', [ProductController::class, 'reduceStock'])->name('variants.reduce-stock');
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/laporan', [HomeController::class, 'laporan'])->name('admin.laporan');
    Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::patch('/orders/{id}/assign-kurir', [KurirController::class, 'assign'])->name('orders.assign-kurir');
    Route::get('/orders/{id}/cetak-resi', [OrderController::class, 'cetakResi'])->name('orders.cetak-resi');
    Route::get('/admin/users/create-kurir', [UserController::class, 'createKurir'])->name('admin.users.create-kurir');
    Route::post('/admin/users/store-kurir', [UserController::class, 'storeKurir'])->name('admin.users.store-kurir');
    Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('/admin/shipping-settings', [ShippingSettingController::class, 'index'])->name('admin.shipping-settings.index');
    Route::put('/admin/shipping-settings', [ShippingSettingController::class, 'update'])->name('admin.shipping-settings.update');
});

// ===== PEMBELI =====
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');

    Route::get('/orders/history', [OrderController::class, 'history'])->name('orders.history');
    Route::get('/orders/manage', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');

    // FIX: throttle di checkout dan payment
    Route::match(['get', 'post'], '/checkout/review', [OrderController::class, 'checkoutReview'])->name('checkout.review');
    Route::post('/checkout', [OrderController::class, 'checkout'])->middleware('throttle:checkout')->name('checkout');
    Route::post('/payment', [PaymentController::class, 'store'])->middleware('throttle:payment')->name('payment.store');
    Route::post('/payment/{id}/confirm', [PaymentController::class, 'confirmPayment'])->middleware('throttle:payment')->name('payment.confirm');

    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{id}/confirm-delivery', [OrderController::class, 'confirmDelivery'])->name('orders.confirm-delivery');
    Route::get('/orders/{id}/invoice/print', [OrderController::class, 'invoicePrint'])->name('orders.invoice-print');

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

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');
});

// ===== KURIR =====
Route::middleware(['auth', 'role:kurir'])->prefix('kurir')->name('kurir.')->group(function () {
    Route::get('/', [KurirController::class, 'index'])->name('index');
    Route::get('/orders/{id}', [KurirController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{id}/status', [KurirController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('/orders/{id}/take', [KurirController::class, 'takeOrder'])->name('orders.take');
    Route::get('/saldo', [KurirController::class, 'saldo'])->name('saldo');
});

Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/search/suggestions', [ProductController::class, 'suggestions'])->name('search.suggestions');

Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

require __DIR__ . '/auth.php';
