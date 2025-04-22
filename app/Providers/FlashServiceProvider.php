<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class FlashServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Inertia::share('flash', function (Request $request) {
            return [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
            ];
        });
    }

}
