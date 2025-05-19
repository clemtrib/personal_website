<?php

namespace App\Services;

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Illuminate\Support\Facades\Session;
use DateTime;

class GoogleMeetService
{
    protected Google_Client $client;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setAuthConfig(storage_path('app/google/credentials.json'));
        $this->client->addScope(Google_Service_Calendar::CALENDAR);
        $this->client->addScope('email');
        $this->client->addScope('profile');
        $this->client->setRedirectUri(route('google.callback'));
        $this->client->setAccessType('offline');
    }

    public function getAuthUrl(): string
    {
        return $this->client->createAuthUrl();
    }

    public function authenticate(string $code): array
    {
        $this->client->authenticate($code);
        return $this->client->getAccessToken();
    }

    public function setAccessToken(array|string $token): void
    {
        $this->client->setAccessToken($token);
    }

    public function createEvent(string $summary, DateTime $startDateTime, DateTime $endDateTime, string $recipientEmail, string $recipientFullname)
    {
        $calendarService = new Google_Service_Calendar($this->client);

        $event = new Google_Service_Calendar_Event([
            'summary' => "{$recipientFullname} - {$summary}",
            'start' => [
                'dateTime' => $startDateTime->format(DateTime::ATOM),
                'timeZone' => 'America/Montreal',
            ],
            'end' => [
                'dateTime' => $endDateTime->format(DateTime::ATOM),
                'timeZone' => 'America/Montreal',
            ],
            'attendees' => [
                ['email' => $recipientEmail],
                ['email' => env('GOOGLE_MEET_EMAIL')],
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

        return $calendarService->events->insert('primary', $event, ['conferenceDataVersion' => 1]);
    }
}
