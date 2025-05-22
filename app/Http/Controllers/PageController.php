<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Page;
use App\Models\Timeslot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class PageController extends Controller
{

    const VALIDATION_RULES = [
        'page_slug' => 'required|string|max:255',
        'page_name' => 'required|string|max:255',
        'hero_subtitle' => 'string|max:255',
        'hero_title' => 'required|string|max:255',
        'hero_description' => 'required|string',
        'hero_image' => 'nullable|image|max:2048',
        'content_text' => 'nullable|string',
        'content_image' => 'nullable|image|max:2048',
        'seo_description' => 'nullable|string|max:255',
        'seo_og_title' => 'nullable|string|max:255',
        'seo_og_description' => 'nullable|string|max:255',
        'seo_twitter_title' => 'nullable|string|max:255',
        'seo_twitter_description' => 'nullable|string|max:255',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = Page::get();
        return Inertia::render('Pages', [
            'pages' => $pages
        ]);
    }

    /**
     *
     */
    public function getPage(string $slug)
    {
        $page = (Page::where('page_slug', $slug)->first());
        $seo = $page->page_seo;
        $timeslots = Timeslot::where('start_datetime', '>', now())
            ->whereNull('summary')
            ->whereNull('recipient_fullname')
            ->whereNull('recipient_email')
            ->orderBy('start_datetime')
            ->get()
            ->groupBy(fn($slot) => Carbon::parse($slot->start_datetime)->toDateString())
            ->keys();
        return Inertia::render(
            'Page',
            [
                'page_slug' => $page->page_slug ?? '',
                'page_name' => $page->page_name ?? '',
                'hero_subtitle' => $page->hero_subtitle ?? '',
                'hero_title' => $page->hero_title ?? '',
                'hero_description' => $page->hero_description ?? '',
                'hero_image' => $page->hero_image ?? '',
                'content_text' => $page->content_text ?? '',
                'content_image' => $page->content_image ?? '',
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
                ],
                'config' => [
                    'social_networks' => [
                        'facebook' => getenv('FACEBOOK') ?? null,
                        'github' => getenv('GITHUB') ?? null,
                        'linkedin' => getenv('LINKEDIN') ?? null,
                    ],
                    'tjm' => getenv('TJM') ?? null,
                ],
                'meetings' => $timeslots
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(self::VALIDATION_RULES);

        $validatedData['hero_image'] = "";
        if ($request->hasFile('hero_image')) {
            $validatedData['hero_image'] = $request->file('hero_image')->store('uploads', 'public');
        }

        $validatedData['content_image'] = "";
        if ($request->hasFile('content_image')) {
            $validatedData['content_image'] = $request->file('content_image')->store('uploads', 'public');
        }

        $page = new Page();
        $page->page_slug = $validatedData['page_slug'];
        $page->page_name = $validatedData['page_name'];
        $page->hero_subtitle = $validatedData['hero_subtitle'];
        $page->hero_title = $validatedData['hero_title'];
        $page->hero_description = $validatedData['hero_description'];
        $page->content_text = $validatedData['content_text'];
        $page->content_image = $validatedData['content_image'] ?? '';
        $page->page_seo = $this->generateSeoProperty($page, $validatedData);

        try {
            $page->save($validatedData);
            $this->forgetCache();
            return response()->json([
                'success' => true,
                'message' => 'Page créée avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function edit(Page $page)
    {
        if (!$page->exists) {
            abort(404, "Page non trouvée");
        }
        return Inertia::render('PagesForm', [
            'page' => $page->only(['id', 'page_slug', 'page_name', 'hero_subtitle', 'hero_title', 'hero_description', 'hero_image', 'content_text', 'content_image', 'page_seo'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page)
    {
        $validatedData = $request->validate(self::VALIDATION_RULES);

        // Supprime l'ancienne image si elle existe
        if (($request->hasFile('hero_image') && $page->hero_image) || ($request->has('remove_hero_image') && $request->boolean('remove_hero_image'))) {
            Storage::disk('public')->delete($page->hero_image);
            $page->hero_image = null;
        }
        if ($request->hasFile('hero_image')) {
            $validatedData['hero_image'] = $request->file('hero_image')->store('uploads', 'public');
        }

        // Supprime l'ancienne image si elle existe
        if (($request->hasFile('content_image') && $page->content_image) || ($request->has('remove_content_image') && $request->boolean('remove_content_image'))) {
            Storage::disk('public')->delete($page->content_image);
            $page->content_image = null;
        }
        if ($request->hasFile('content_image')) {
            $validatedData['content_image'] = $request->file('content_image')->store('uploads', 'public');
        }

        $page->page_seo = $this->generateSeoProperty($page, $validatedData);

        try {
            $page->update($validatedData);
            $this->forgetCache();
            return response()->json([
                'success' => true,
                'message' => 'Page modifiée avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        try {
            $page->delete();
            $this->forgetCache();
            return to_route('pages', ['json' => false])->with('success', 'Page supprimée  avec succès');
        } catch (\Exception $e) {
            return back()->with('error',  $e->getMessage());
        }
    }

    /**
     *
     */
    private function generateSeoProperty(Page $page, array $validatedData): array
    {
        $seo_image = null;
        if (isset($validatedData['hero_image'])) {
            $seo_image = $validatedData['hero_image'];
        } elseif ($page->hero_image) {
            $seo_image = $page->hero_image;
        }
        return [
            "description" => $validatedData['seo_description'] ?? '',
            "canonical" => config('app.url'),
            "og:title" => $validatedData['seo_og_title'] ?? config('app.name'),
            "og:description" => $validatedData['seo_og_description'] ?? '',
            "og:image" => $seo_image ? config('app.url') . '/uploads-ovh/' . basename($seo_image) : '',
            "og:url" => config('app.url'),
            "og:type" => "website",
            "og:site_name" => config('app.name'),
            "twitter:card" => "summary_large_image",
            "twitter:title" =>  $validatedData['seo_twitter_title'] ?? config('app.name'),
            "twitter:description" => $validatedData['seo_twitter_description'] ?? '',
            "twitter:image" =>  $seo_image ? config('app.url') . '/uploads-ovh/' . basename($seo_image) : '',
        ];
    }
}
