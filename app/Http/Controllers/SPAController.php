<?php

namespace App\Http\Controllers;

use App\Models\WorkExperience;
use App\Models\Education;
use App\Models\Hobby;
use App\Models\Skill;
use App\Models\Page;
use Illuminate\Support\Facades\Cache;

class SPAController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $key = config('app.cache_key');
        if (Cache::has($key)) {
            $value = array_merge(Cache::get($key), ['cache' => 1]);
        } else {
            $response = [
                'config' => [
                    'social_networks' => [
                        'facebook' => getenv('FACEBOOK') ?? null,
                        'github' => getenv('GITHUB') ?? null,
                        'linkedin' => getenv('LINKEDIN') ?? null,
                    ],
                    'tjm' => getenv('TJM') ?? null,
                ],
                'experiences' => WorkExperience::orderByRaw('end_at IS NOT NULL, end_at DESC')->get(),
                'schools' => Education::orderByRaw('date IS NOT NULL, date DESC')->get(),
                'hobbies' => Hobby::orderBy('order', 'asc')->get(),
                'skills' => Skill::orderBy('order', 'asc')->get(),
                'content' => Page::where('page_slug', 'home')->first()
            ];
            Cache::add($key, $response, now()->addHours(12));
            $value = array_merge($response, ['cache' => 0]);
        }
        return response()->json($value);
    }
}
