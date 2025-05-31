<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

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

        // Regénérer le CV au format PDF
        $filepath = Storage::disk('local')->path("cv.pdf");
        if (file_exists($filepath)) {
            unlink($filepath);
        }
    }

    /**
     *
     */
    protected function verifyCaptcha(Request $request): bool
    {
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => getenv('RECAPTCHA_PRIVATE_KEY') ?? null,
            'response' => $request->recaptcha_token,
            'remoteip' => $request->ip(),
        ]);

        $result = $response->json();

        if (!($result['success'] ?? false) || ($result['score'] ?? 0) < 0.5) {
            return false;
        }
        return true;
    }
}
