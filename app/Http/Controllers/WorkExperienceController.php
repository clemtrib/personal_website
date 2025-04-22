<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\WorkExperience;
use Illuminate\Http\Request;

class WorkExperienceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(bool $json = true)
    {
        $workExperiences = WorkExperience::orderBy('end_at', 'desc')->get();
        if($json) {
            return response()->json($workExperiences);
        } else {
            return Inertia::render('WorkExperiences', [
                'experiences' => $workExperiences,
                'flash' => [
                    'success' => session('success')
                ]
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'location' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'job' => 'required|string|max:255',
            'description' => 'string',
            'begin_at' => 'required',
            'end_at' => ''
        ]);

        $message = new WorkExperience();
        $message->location = $validatedData['location'];
        $message->company = $validatedData['company'];
        $message->job = $validatedData['job'];
        $message->description = $validatedData['description'];
        $message->begin_at = $validatedData['begin_at'];
        $message->end_at = $validatedData['end_at'];
        $message->save();

        return response()->json(['message' => 'Expérience enregistrée avec succès'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function edit(WorkExperience $workExperience)
    {

        if(!$workExperience->exists) {
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
            $validatedData = $request->validate([
                'location' => 'sometimes|required|string|max:255',
                'company' => 'sometimes|required|string|max:255',
                'job' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|string',
                'begin_at' => 'sometimes|required',
                'end_at' => 'sometimes'
            ]);

            try {
                $workExperience->update($validatedData);
                return redirect()->route('experiences', ['json' => false])->with('success', 'Expérience mise à jour avec succès');
            } catch (\Exception $e) {
                return back()->with('error', 'Erreur lors de la mise à jour');
            }

            //$workExperience->update($validatedData);
            //return redirect()->route('experiences', ['json' => false])->with('success', 'Expérience mise à jour avec succès');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkExperience $workExperience)
    {
        $workExperience->delete();
        return response()->json(['message' => 'Expérience supprimée avec succès']);
    }
}
