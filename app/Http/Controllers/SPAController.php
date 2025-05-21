<?php

namespace App\Http\Controllers;

use App\Models\WorkExperience;
use App\Models\Education;
use App\Models\Hobby;
use App\Models\Skill;
use App\Models\Page;
use App\Models\Timeslot;
use App\Models\Guser;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class SPAController extends Controller
{

    public function load()
    {
        $seo = (Page::where('page_slug', 'home')->first())->page_seo;
        return Inertia::render('Welcome')->withViewData([
            "meta" => [
                "description" => $seo['description'] ?? '',
                "canonical" => $seo['canonical'] ?? '',
                "og:title" => $seo['og:title'] ?? '',
                "og:description" => $seo['og:description'] ?? '',
                "og:image" => $seo['og:image'] ?? '',
                "og:url" => $seo['og:url'] ?? '',
                "og:type" => $seo['og:type'] ?? '',
                "og:site_name" => $seo['og:site_name'] ?? '',
                "twitter:card" => $seo['twitter:card'] ?? '',
                "twitter:title" =>  $seo['twitter:title'] ?? '',
                "twitter:description" => $seo['twitter:description'] ?? '',
                "twitter:image" =>  $seo['twitter:image'] ?? '',
            ]
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $key = config('app.cache_key');
        $timeslots = Timeslot::where('start_datetime', '>', now())
            ->whereNull('summary')
            ->whereNull('recipient_fullname')
            ->whereNull('recipient_email')
            ->orderBy('start_datetime')
            ->get()
            ->groupBy(fn($slot) => Carbon::parse($slot->start_datetime)->toDateString())
            ->keys();
        $google_auth = Guser::find(Session::get('guser_id'));
        $user_meet = null;
        if ($google_auth) {
            $user_meet = Timeslot::select('start_datetime', 'end_datetime')
                ->where('start_datetime', '>', now())
                ->where('recipient_email', '=', $google_auth->google_email)
                ->orderBy('start_datetime')
                ->first();
        }
        if (Cache::has($key)) {
            $value = array_merge(Cache::get($key), ['cache' => 1, 'meetings' => $timeslots, 'google_auth' => $google_auth, 'user_meet' => $user_meet]);
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
                'content' => Page::where('page_slug', 'home')->first(),
                'google_auth_url' => route('google.auth'),
            ];
            Cache::add($key, $response, now()->addHours(12));
            $value = array_merge($response, ['cache' => 0, 'meetings' => $timeslots, 'google_auth' => $google_auth, 'user_meet' => $user_meet]);
        }
        return response()->json($value);
    }

    /**
     *
     */
    public function getMeetingTimeslots(string $date)
    {
        $timeslots = Timeslot::where('start_datetime', '>=', $date . ' 00:00:00')
            ->where('start_datetime', '<=', $date . ' 23:59:59')
            ->whereNull('summary')
            ->whereNull('recipient_fullname')
            ->whereNull('recipient_email')
            ->orderBy('start_datetime')
            ->get();
        return response()->json($timeslots);
    }
}
