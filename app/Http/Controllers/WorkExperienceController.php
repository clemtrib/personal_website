<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\WorkExperience;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class WorkExperienceController extends Controller implements HasMiddleware
{

    const VALIDATION_RULES = [
        'location' => 'required|string|max:255',
        'company' => 'required|string|max:255',
        'job' => 'required|string|max:255',
        'description' => 'string',
        'begin_at' => 'required|date_format:Y-m-d',
        'end_at' => 'nullable|date_format:Y-m-d|after:begin_at'
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
        $workExperiences = WorkExperience::orderByRaw('end_at IS NOT NULL, end_at DESC')->get();
        if ($json) {
            return response()->json($workExperiences);
        } else {
            return Inertia::render('WorkExperiences', [
                'experiences' => $workExperiences
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(self::VALIDATION_RULES);

        $workExperience = new WorkExperience();
        $workExperience->location = $validatedData['location'];
        $workExperience->company = $validatedData['company'];
        $workExperience->job = $validatedData['job'];
        $workExperience->description = $validatedData['description'];
        $workExperience->begin_at = $validatedData['begin_at'];
        $workExperience->end_at = $validatedData['end_at'];

        try {
            $workExperience->save();
            return redirect()->route('experiences', ['json' => false])->with('success', 'Expérience créée avec succès');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la création');
        }

        return response()->json(['message' => 'Expérience enregistrée avec succès'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function edit(WorkExperience $workExperience)
    {
        if (!$workExperience->exists) {
            abort(404, "Expérience non trouvée");
        }
        return Inertia::render('WorkExperiencesForm', [
            'experience' => $workExperience->only(['id', 'location', 'company', 'job', 'description', 'begin_at', 'end_at'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WorkExperience $workExperience)
    {
        $validatedData = $request->validate(self::VALIDATION_RULES);

        try {
            $workExperience->update($validatedData);
            return redirect()->route('experiences', ['json' => false])->with('success', 'Expérience modifiée avec succès');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la modification');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkExperience $workExperience)
    {
        try {
            $workExperience->delete();
            return redirect()->route('experiences', ['json' => false])->with('success', 'Expérience supprimée avec succès');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la supression');
        }
    }
}
