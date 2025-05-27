<?php

namespace App\Services;

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use DateTime;
use DateTimeImmutable;
use App\Models\Guser;

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
        $this->client->setRedirectUri(getenv('GOOGLE_REDIRECT_URI'));
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

    public function createEvent(string $summary, DateTime|DateTimeImmutable $startDateTime, DateTime|DateTimeImmutable $endDateTime, string $recipientEmail, string $recipientFullname)
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

    public function setUser(Guser $user): void
    {
        $token = json_decode($user->google_access_token, true);
        $this->client->setAccessToken($token);

        if ($this->client->isAccessTokenExpired()) {
            if (!$user->google_refresh_token) {
                throw new \Exception("Refresh token manquant. Veuillez vous reconnecter.");
            }

            $newToken = $this->client->fetchAccessTokenWithRefreshToken($user->google_refresh_token);

            $user->update([
                'google_access_token' => json_encode($newToken),
                'google_token_expires_at' => now()->addSeconds($newToken['expires_in']),
            ]);

            $this->client->setAccessToken($newToken);
        }
    }
}
