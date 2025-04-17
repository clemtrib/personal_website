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
    public function index()
    {
        $workExperiences = WorkExperience::orderBy('created_at', 'desc')->get();
        return response()->json($workExperiences);
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
    public function show(WorkExperience $workExperience)
    {
        return Inertia::render('WorkExperiencesForm', [
            'experience' => $workExperience
        ]);
        //return response()->json($workExperience);
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

        $workExperience->update($validatedData);

        return response()->json(['message' => 'Expérience mis à jour avec succès']);

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
