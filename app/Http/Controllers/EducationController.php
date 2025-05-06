<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Education;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    const VALIDATION_RULES = [
        'school' => 'required|string|max:255',
        'graduation' => 'string',
        'date' => 'required|date_format:Y-m-d',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $educations = Education::orderByRaw('date IS NOT NULL, date DESC')->get();
        return Inertia::render('Education', [
            'educations' => $educations
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(self::VALIDATION_RULES);

        $education = new Education();
        $education->school = $validatedData['school'];
        $education->graduation = $validatedData['graduation'];
        $education->date = $validatedData['date'];

        try {
            $education->save();
            $this->forgetCache();
            return to_route('education', ['json' => false])->with('success', 'Diplôme créée avec succès');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function edit(Education $education)
    {
        if (!$education->exists) {
            abort(404, "Expérience non trouvée");
        }
        return Inertia::render('EducationForm', [
            'education' => $education->only(['id', 'school', 'graduation', 'date'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Education $education)
    {
        $validatedData = $request->validate(self::VALIDATION_RULES);

        try {
            $education->update($validatedData);
            $this->forgetCache();
            return to_route('education', ['json' => false])->with('success', 'Diplôme modifié avec succès');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Education $education)
    {
        try {
            $education->delete();
            $this->forgetCache();
            return to_route('education', ['json' => false])->with('success', 'Diplôme supprimé avec succès');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
