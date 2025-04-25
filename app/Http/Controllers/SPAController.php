<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\WorkExperience;
use App\Models\Education;
use App\Models\Hobby;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SPAController extends Controller
{

    const CACHE_KEY = '';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        //if (Cache::has(self::CACHE_KEY)) {
        //  $value = Cache::get(self::CACHE_KEY);
        //} else {
        $value = [
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
            'hobbies' => Hobby::orderBy('order ASC')->get(),
            'skills' => Skill::orderBy('order ASC')->get(),
        ];
        Cache::add(self::CACHE_KEY, $value, now()->addHours(12));
        //}
        return response()->json($value);
    }
}
