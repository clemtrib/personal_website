<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;

abstract class Controller
{

    /**
     *
     */
    protected function forgetCache(): void
    {
        $key = config('app.cache_key');

        if ($key && Cache::has($key)) {
            Cache::forget($key);
        }
    }
}
