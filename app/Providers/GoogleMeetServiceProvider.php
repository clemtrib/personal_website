<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class GoogleMeetServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    public function create(string $summary, \DateTime $startDateTime, \DateTime $endDateTime, string $recipient)
    {
        $client->setAccessToken($_SESSION['access_token']);

        $calendarService = new Google_Service_Calendar($client);

        // Paramètres de l'événement
        $event = new Google_Service_Calendar_Event([
            'summary' => $summary,
            'start' => [
                'dateTime' => $startDateTime->format('c'),
                'timeZone' => 'America/Montreal',
            ],
            'end' => [
                'dateTime' => $endDateTime->format('c'),
                'timeZone' => 'America/Montreal',
            ],
            'attendees' => [
                ['email' => $recipient],
                ['email' => getenv('GOOGLE_MEET_EMAIL')],
            ],
            'conferenceData' => [
                'createRequest' => [
                    'requestId' => uniqid(),
                    'conferenceSolutionKey' => [
                        'type' => 'hangoutsMeet',
                    ],
                ],
            ],
        ]);

        $calendarId = 'primary'; // ou l'ID d’un calendrier partagé
        $event = $calendarService->events->insert($calendarId, $event, ['conferenceDataVersion' => 1]);

        echo "Meet link: " . $event->getHangoutLink();
    }
}
