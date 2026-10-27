<?php

namespace App\Providers;

use App\Models\Claim;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Navbar badge: pending claims on the signed-in user's open items.
        View::composer('layouts.navigation', function ($view) {
            $toReview = Auth::check()
                ? Claim::where('status', 'pending')
                    ->whereHas('item', fn ($q) => $q->where('user_id', Auth::id())->where('status', 'open'))
                    ->count()
                : 0;

            $view->with('toReview', $toReview);
        });
    }
}
