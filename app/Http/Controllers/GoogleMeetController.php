<?php

namespace App\Http\Controllers;

use App\Models\Timeslot;
use App\Services\GoogleMeetService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class GoogleMeetController extends Controller
{

    const VALIDATION_RULES = [
        'summary' => 'nullable|string|max:255',
    ];

    const VALIDATION_RULES_GENERATE_MEETS = [
        'days_multiple' => ['required', 'array'],
        'days_multiple.*' => ['in:1,2,3,4,5,6,7'], // 1=Lundi, 7=Dimanche (par exemple)
        'duration' => ['required', 'in:15,30,45,60,90'],
        'datetime_range' => ['required', 'array', 'size:2'],
        'datetime_range.*' => ['required', 'date_format:Y-m-d\TH:i:sP'],
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $timeslots = Timeslot::where('start_datetime', '>=', \Illuminate\Support\Carbon::tomorrow())
            ->orderBy('start_datetime')
            ->get();

        return Inertia::render('Meets', [
            'timeslots' => $timeslots,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(self::VALIDATION_RULES_GENERATE_MEETS);

        $start = new \DateTime($validatedData['datetime_range'][0], new \DateTimeZone(getenv('TIME_ZONE')));
        $end = new \DateTime($validatedData['datetime_range'][1], new \DateTimeZone(getenv('TIME_ZONE')));
        $duration = (int) $validatedData['duration'];
        $targetDays = array_map('intval', $validatedData['days_multiple']); // convertit ["3", "4", "5"] en [3, 4, 5]

        $interval = new \DateInterval("PT{$duration}M"); // 30 min interval

        // Parcours de chaque jour de la plage
        $period = new \DatePeriod($start, new \DateInterval('P1D'), $end->modify('+1 day'));

        $h_start = ($start->format('H') < $end->format('H')) || ($start->format('H') == $end->format('H') && $start->format('H') < $end->format('H')) ? $start : $end;
        $h_end = $start == $h_start ? $end : $start;

        $i = 0;

        try {
            foreach ($period as $day) {
                // Vérifie si c'est un des jours sélectionnés
                if (in_array((int) $day->format('N'), $targetDays)) {
                    // Génère les horaires entre 10h et 16h
                    $slotStart = clone $day;
                    $slotStart->setTime($h_start->format('H'), $h_start->format('i')); // 10h00

                    $slotEnd = clone $day;
                    $slotEnd->setTime($h_end->format('H'), $h_end->format('i')); // 16h00

                    while ($slotStart < $slotEnd) {
                        $nextSlot = (clone $slotStart)->add($interval);

                        $timeslot = new Timeslot();
                        $timeslot->summary = null;
                        $timeslot->recipient_email = null;
                        $timeslot->recipient_fullname = null;
                        $timeslot->start_datetime = $slotStart->format('Y-m-d H:i:s');
                        $timeslot->end_datetime = $nextSlot->format('Y-m-d H:i:s');
                        $timeslot->link = null;
                        $timeslot->save();
                        $i++;

                        $slotStart = $nextSlot;
                    }
                }
            }
            return to_route('meets')->with('success', "{$i} plages horaires créées  avec succès");
        } catch (\Exception $e) {
            return to_route('meets')->with('error', $e->getMessage());
        }
    }

    /**
     *
     */
    public function book(Request $request, GoogleMeetService $meet, Timeslot $timeslot)
    {

        if (!$this->verifyCaptcha($request)) {
            return back()->with([
                'flash' =>
                ['failure_meeting' => 'Échec de la vérification reCAPTCHA.']
            ]);
        }

        if ($timeslot->recipient_email !== null) {
            return back()->with([
                'flash' =>
                ['failure_meeting' => 'Cette plage horaire a déjà été réservé.']
            ]);
        }


        $validatedData = $request->validate(self::VALIDATION_RULES);

        $token = Session::get('google_token');

        $guser = \App\Models\Guser::find(Session::get('guser_id'));

        if (!$token) {
            return redirect()->route('google.auth');
        }

        $meet->setAccessToken($token);

        if (!$guser) {
            return back()->with([
                'flash' => [
                    'failure_meeting' => 'Session expirée, veuillez vous reconnecter.',
                    'failure_meeting_link' =>  $meet->getAuthUrl()
                ]
            ]);
        }

        try {
            $meet->setUser($guser);
        } catch (\Exception $e) {
            return back()->with([
                'flash' => [
                    'failure_meeting' => 'Session expirée, veuillez vous reconnecter.',
                    'failure_meeting_link' =>  $meet->getAuthUrl()
                ]
            ]);
        }

        $user_meet = Timeslot::select('start_datetime', 'end_datetime', 'link')
            ->where('start_datetime', '>', now())
            ->where('recipient_email', '=', $guser->google_email)
            ->orderBy('start_datetime')
            ->first();
        if ($user_meet) {
            return back()->with([
                'flash' =>
                ['failure_meeting' => sprintf('Nous avons déjà rendez-vous le %s à %s.', new \DateTime($user_meet->start_datetime, new \DateTimeZone(getenv('TIME_ZONE')))->format('d/m/Y'), new \DateTime($user_meet->start_datetime, new \DateTimeZone(getenv('TIME_ZONE')))->format('H:i'))]
            ]);
        }

        $timeslot->recipient_email = $guser->google_email;
        $timeslot->recipient_fullname = $guser->google_name;

        $startDt = new \DateTimeImmutable($timeslot->start_datetime, new \DateTimeZone(getenv('TIME_ZONE')));
        $endDt = new \DateTimeImmutable($timeslot->end_datetime, new \DateTimeZone(getenv('TIME_ZONE')));

        $event = $meet->createEvent(
            $validatedData['summary'],
            $startDt,
            $endDt,
            $guser->google_email,
            $guser->google_name
        );

        $validatedData['link'] = $event->getHangoutLink();

        try {
            $timeslot->update($validatedData);

            return back()->with([
                'flash' => [
                    'success_meeting' => 'Votre rendez-vous est confirmé !<br />Une invitation Google Meet va vous être envoyée par email.',
                    'confirmed_meeting' => [
                        'date' => $startDt->format('d/m/Y'),
                        'start' => $startDt->format('H:i'),
                        'end' => $endDt->format('H:i'),
                        'link' => $event->getHangoutLink(),
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return back()->with([
                'flash' =>
                ['failure_meeting' => $e->getMessage()]
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Timeslot $timeslot)
    {
        try {
            $timeslot->delete();
            return to_route('meets', ['json' => false])->with('success', 'Plage horaire supprimée  avec succès');
        } catch (\Exception $e) {
            return back()->with('error',  $e->getMessage());
        }
    }
}
