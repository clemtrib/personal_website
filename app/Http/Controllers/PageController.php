<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller implements HasMiddleware
{

    const VALIDATION_RULES = [
        'page_slug' => 'required|string|max:255',
        'page_name' => 'required|string|max:255',
        'hero_subtitle' => 'string|max:255',
        'hero_title' => 'required|string|max:255',
        'hero_description' => 'required|string',
        //'hero_image' => 'nullable|image|max:2048',
        //'content_text' => 'nullable|string',
        'content_image' => 'nullable|image|max:2048',
        //'page_seo' => 'nullable|string',
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

        if ($request->hasFile('content_image')) {
            $validatedData['content_image'] = $request->file('content_image')->store('uploads', 'public');
        }

        $page = new Page();
        $page->page_slug = $validatedData['page_slug'];
        $page->page_name = $validatedData['page_name'];
        $page->hero_subtitle = $validatedData['hero_subtitle'];
        $page->hero_title = $validatedData['hero_title'];
        $page->hero_description = $validatedData['hero_description'];
        //$page->hero_image = $validatedData['hero_image'];
        //$page->content_text = $validatedData['content_text'];
        $page->content_image = $validatedData['content_image'];
        //$page->page_seo = $validatedData['page_seo'];

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
    public function edit(Page $page)
    {
        if (!$page->exists) {
            abort(404, "Page non trouvée");
        }
        return Inertia::render('PagesForm', [
            'page' => $page->only(['id', 'page_slug', 'page_name', 'hero_subtitle', 'hero_title', 'hero_description', /*'hero_image', 'content_text',*/ 'content_image'/*, 'page_seo'*/])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page)
    {

        $validatedData = $request->validate(self::VALIDATION_RULES);

        if ($request->hasFile('content_image')) {
            // Supprime l'ancienne image si elle existe
            if ($page->content_image) {
                Storage::disk('public')->delete($page->content_image);
            }

            $validatedData['content_image'] = $request->file('content_image')->store('uploads', 'public');
        }

        if ($request->has('remove_content_image') && $request->boolean('remove_content_image')) {
            if ($page->content_image) {
                Storage::disk('public')->delete($page->content_image);
                $page->content_image = null;
            }
        }

        try {
            $page->update($validatedData);
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
            return to_route('pages', ['json' => false])->with('success', 'Page supprimée  avec succès');
        } catch (\Exception $e) {
            return back()->with('error',  $e->getMessage());
        }
    }
}
