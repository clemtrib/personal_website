<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PageController extends Controller implements HasMiddleware
{

    const VALIDATION_RULES = [
        'page_slug' => 'required|string|max:255',
        'page_name' => 'required|string|max:255',
        'hero_subtitle' => 'string|max:255',
        'hero_title' => 'required|string|max:255',
        'hero_description' => 'required|string',
        'hero_image' => 'nullable|string|max:255',
        'content_text' => 'nullable|string',
        'content_image' => 'nullable|string|max:255',
        'page_seo' => 'nullable|string',
    ];

    public static function middleware(): array
    {
        return [];
        /*
        return [
            'auth',
            new Middleware('can:edit-experiences', except: ['index', 'show'])
        ];
        */
    }

    /**
     * Display a listing of the resource.
     */
    public function index(bool $json = true)
    {
        $pages = Page::get();
        if ($json) {
            return response()->json($pages);
        } else {
            return Inertia::render('pages', [
                'pages' => $pages
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(self::VALIDATION_RULES);

        $page = new Page();
        $page->page_slug = $validatedData['page_slug'];
        $page->page_name = $validatedData['page_name'];
        $page->hero_subtitle = $validatedData['hero_subtitle'];
        $page->hero_title = $validatedData['hero_title'];
        $page->hero_description = $validatedData['hero_description'];
        $page->hero_image = $validatedData['hero_image'];
        $page->content_text = $validatedData['content_text'];
        $page->content_image = $validatedData['content_image'];
        $page->page_seo = $validatedData['page_seo'];

        try {
            $page->save();
            return to_route('pages', ['json' => false])->with('success', 'Page créée avec succès');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function edit(page $page)
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
    public function update(Request $request, page $page)
    {
        $validatedData = $request->validate(self::VALIDATION_RULES);

        try {
            $page->update($validatedData);
            return to_route('pages', ['json' => false])->with('success', 'Page modifiée avec succès');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(page $page)
    {
        try {
            $page->delete();
            return to_route('pages', ['json' => false])->with('success', 'Page supprimée  avec succès');
        } catch (\Exception $e) {
            return back()->with('error',  $e->getMessage());
        }
    }
}
