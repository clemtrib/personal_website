<?php

namespace App\Http\Controllers;

use App\Models\Timeslot;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GoogleMeetController extends Controller
{

    const VALIDATION_RULES = [
        'summary' => 'nullable|string|max:255',
        'recipient_email' => 'nullable|email|max:255',
        'recipient_fullname' => 'nullable|string|max:255',
        'start_datetime' => 'required|date_format:Y-m-d H:i:s',
        'end_datetime' => 'required|date_format:Y-m-d H:i:s',
    ];


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $timeslots = Timeslot::get();
        return Inertia::render('Meets', [
            'timeslots' => $timeslots
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(self::VALIDATION_RULES);

        $timeslot = new Timeslot();
        $timeslot->summary = $validatedData['summary'] ?? null;
        $timeslot->recipient_email = $validatedData['recipient_email'] ?? null;
        $timeslot->recipient_fullname = $validatedData['recipient_fullname'] ?? null;
        $timeslot->start_datetime = $validatedData['start_datetime'];
        $timeslot->end_datetime = $validatedData['end_datetime'];

        try {
            $timeslot->save($validatedData);

            var_dump('siccess');
            die;

            return response()->json([
                'success' => true,
                'message' => 'Page horaire créée avec succès'
            ]);
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            die;

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function edit(Timeslot $timeslot)
    {
        if (!$timeslot->exists) {
            abort(404, "Page non trouvée");
        }
        return Inertia::render('MeetsForm', [
            'page' => $$timeslot->only(['id', 'summary', 'recipient_email', 'recipient_fullname', 'start_datetime', 'end_datetime'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Timeslot $timeslot)
    {
        $validatedData = $request->validate(self::VALIDATION_RULES);
        try {
            $timeslot->update($validatedData);
            return response()->json([
                'success' => true,
                'message' => 'Plage horaire modifiée avec succès'
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
    public function destroy(Timeslot $timeslot)
    {
        try {
            $timeslot->delete();
            return to_route('Meets', ['json' => false])->with('success', 'Plage horaire supprimée  avec succès');
        } catch (\Exception $e) {
            return back()->with('error',  $e->getMessage());
        }
    }
}
