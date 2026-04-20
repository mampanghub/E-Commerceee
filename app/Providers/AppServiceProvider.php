<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (str_contains(request()->getHost(), 'ngrok')) {
            URL::forceScheme('https');
        }

        View::composer('*', function ($view) {
            if (Auth::check()) {
                if (Auth::user()->role === 'admin') {
                    $view->with(
                        'pendingOrderCount',
                        Order::whereIn('status', ['dibayar', 'dikemas'])->count()
                    );
                }

                if (Auth::user()->role === 'pembeli') {
                    $view->with(
                        'cartCount',
                        \DB::table('cart_items')
                            ->join('carts', 'cart_items.cart_id', '=', 'carts.cart_id')
                            ->where('carts.user_id', Auth::id())
                            ->count()
                    );
                }
            }
        });
    }
}
