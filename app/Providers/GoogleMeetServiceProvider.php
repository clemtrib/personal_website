<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\GoogleMeetService;

class GoogleMeetServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(GoogleMeetService::class, function ($app) {
            return new GoogleMeetService();
        });
    }

}
