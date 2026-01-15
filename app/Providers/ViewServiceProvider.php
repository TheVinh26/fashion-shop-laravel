<?php

namespace App\Providers;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // View::composer('*', function ($view) {
        //     $cartCount = 0;

        //     if (Auth::check()) {
        //         $cart = Cart::with('items')
        //             ->where('user_id', Auth::id())
        //             ->first();

        //         if ($cart) {
        //             $cartCount = $cart->items->sum('quantity');
        //         }
        //     }

        //     $view->with('cartCount', $cartCount);
        // });

        View::composer('*', function ($view) {
            $cartCount = 0;

            if (Auth::check()) {
                $cart = Cart::with('items')
                    ->where('user_id', Auth::id())
                    ->first();

                $cartCount = $cart?->items->sum('quantity') ?? 0;
            }

            $view->with('cartCount', $cartCount);
        });
    }
}
