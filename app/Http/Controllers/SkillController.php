<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{

    const VALIDATION_RULES = [
        'label' => 'required|string|max:255',
        'order' => '',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(bool $json = true)
    {
        $skills = Skill::orderBy('order ASC')->get();
        return Inertia::render('Skills', [
            'skills' => $skills
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(self::VALIDATION_RULES);

        $skill = new Skill();
        $skill->label = $validatedData['label'];
        $skill->order = $validatedData['order'];

        try {
            $skill->save();
            $this->forgetCache();
            return to_route('skills', ['json' => false])->with('success', 'Compétence créé avec succès');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function edit(Skill $skill)
    {
        if (!$skill->exists) {
            abort(404, "Loisirs non trouvée");
        }
        return Inertia::render('SkillsForm', [
            'skill' => $skill->only(['id', 'label', 'order'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Skill $skill)
    {
        $validatedData = $request->validate(self::VALIDATION_RULES);

        try {
            $skill->update($validatedData);
            $this->forgetCache();
            return to_route('skills', ['json' => false])->with('success', 'Compétence modifié avec succès');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Skill $skill)
    {
        try {
            $skill->delete();
            $this->forgetCache();
            return to_route('skills', ['json' => false])->with('success', 'Compétence supprimé  avec succès');
        } catch (\Exception $e) {
            return back()->with('error',  $e->getMessage());
        }
    }
}
