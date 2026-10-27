<?php

namespace App\Providers;

use App\Models\Claim;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
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
        // On the live site every URL Laravel generates (forms, redirects, photos)
        // must be https, or browsers block the forms as "not secure".
        if ($this->app->environment('production') && str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

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
