<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class OvhController extends Controller
{

    /**
     *
     * @param string $filename
     */
    public function getFile(string $filename)
    {
        $path = storage_path('app/public/uploads/' . basename($filename));
        if (!File::exists($path)) {
            abort(404);
        }
        return response()->file($path);
    }

    /**
     *
     * @param Request $request
     */
    public function runMigrations(Request $request)
    {
        // Protection par token
        $token = $request->query('token');

        if ($token !== env('MIGRATION_WEBHOOK_TOKEN')) {
            abort(403, 'Unauthorized');
        }

        // Exécute les migrations en production (sans confirmation)
        Artisan::call('migrate', ['--force' => true]);

        return response()->json([
            'status' => 'success',
            'output' => Artisan::output()
        ]);
    }

    /**
     *
     * @param Request $request
     */
    public function clearCache(Request $request)
    {
        // Protection par token
        $token = $request->query('token');

        if ($token !== env('MIGRATION_WEBHOOK_TOKEN')) {
            abort(403, 'Unauthorized');
        }

        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        return response()->json([
            'status' => 'success',
            'output' => Artisan::output()
        ]);
    }

    /**
     * Crée le lien symbolique public/storage → storage/app/public.
     *
     * @param Request $request
     */
    public function storageLink(Request $request)
    {
        // Protection par token
        $token = $request->query('token');

        if ($token !== env('MIGRATION_WEBHOOK_TOKEN')) {
            abort(403, 'Unauthorized');
        }

        Artisan::call('storage:link');
        return response()->json([
            'status' => 'success',
            'output' => Artisan::output()
        ]);
    }
}
