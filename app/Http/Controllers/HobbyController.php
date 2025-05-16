<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Hobby;
use Illuminate\Http\Request;

class HobbyController extends Controller
{

    const VALIDATION_RULES = [
        'title' => 'required|string|max:255',
        'description' => 'string|max:255',
        'order' => '',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hobbies = Hobby::orderBy('order', 'ASC')->get();
        return Inertia::render('Hobbies', [
            'hobbies' => $hobbies
        ]);
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
            return to_route('hobbies')->with('success', 'Loisirs créé avec succès');
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
            return to_route('hobbies')->with('success', 'Loisirs modifié avec succès');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     *
     */
    public function reorder(Request $request)
    {
        try {
            $data = $request->validate([
                'hobbies' => 'required|array',
                'hobbies.*.id' => 'required|exists:hobbies,id',
                'hobbies.*.order' => 'required|integer',
            ]);

            foreach ($data['hobbies'] as $hobbyData) {
                Hobby::where('id', $hobbyData['id'])->update(['order' => $hobbyData['order']]);
            }

            $this->forgetCache();
            return response()->json(['success' => true]);
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
            return to_route('hobbies')->with('success', 'Loisirs supprimé  avec succès');
        } catch (\Exception $e) {
            return back()->with('error',  $e->getMessage());
        }
    }
}
