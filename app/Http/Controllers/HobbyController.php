<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Hobby;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class HobbyController extends Controller implements HasMiddleware
{

    const VALIDATION_RULES = [
        'title' => 'required|string|max:255',
        'description' => 'string|max:255',
        'order' => '',
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
        $hobbies = Hobby::orderBy('order ASC')->get();
        if ($json) {
            return response()->json($hobbies);
        } else {
            return Inertia::render('Hobbies', [
                'hobbies' => $hobbies
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(self::VALIDATION_RULES);

        $hobby = new Hobby();
        $hobby->title = $validatedData['title'];
        $hobby->description = $validatedData['description'];
        $hobby->order = $validatedData['order'];

        try {
            $hobby->save();
            $this->forgetCache();
            return to_route('hobbies', ['json' => false])->with('success', 'Loisirs créé avec succès');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function edit(Hobby $hobby)
    {
        if (!$hobby->exists) {
            abort(404, "Loisirs non trouvée");
        }
        return Inertia::render('HobbiesForm', [
            'hobby' => $hobby->only(['id', 'title', 'description', 'order'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Hobby $hobby)
    {
        $validatedData = $request->validate(self::VALIDATION_RULES);

        try {
            $hobby->update($validatedData);
            $this->forgetCache();
            return to_route('hobbies', ['json' => false])->with('success', 'Loisirs modifié avec succès');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hobby $hobby)
    {
        try {
            $hobby->delete();
            $this->forgetCache();
            return to_route('hobbies', ['json' => false])->with('success', 'Loisirs supprimé  avec succès');
        } catch (\Exception $e) {
            return back()->with('error',  $e->getMessage());
        }
    }
}
