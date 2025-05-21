<?php

namespace App\Http\Controllers;

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Oauth2;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Guser;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        $client = new Google_Client();
        $client->setAuthConfig(storage_path('app/google/credentials.json'));
        $client->addScope(Google_Service_Calendar::CALENDAR);
        $client->addScope('email');
        $client->addScope('profile');
        $client->setAccessType('offline');
        $client->setRedirectUri(route('google.callback'));

        return redirect($client->createAuthUrl());
    }

    public function callback(Request $request)
    {
        $client = new Google_Client();
        $client->setAuthConfig(storage_path('app/google/credentials.json'));
        $client->addScope(Google_Service_Calendar::CALENDAR);
        $client->addScope('email');
        $client->addScope('profile');
        $client->setAccessType('offline');
        $client->setPrompt('consent');
        $client->setRedirectUri(route('google.callback'));

        if ($request->has('code')) {
            $token = $client->fetchAccessTokenWithAuthCode($request->get('code'));
            Session::put('google_token', $token);


            // Définir le token sur le client
            $client->setAccessToken($token);

            // Récupérer les infos de l'utilisateur
            $oauth2 = new Google_Service_Oauth2($client);
            $userInfo = $oauth2->userinfo->get();

            //echo '<pre>';
            //var_dump($userInfo, $userInfo->id);
            //die;

            // Exemple : récupérer l'email
            // TODO remeove this
            Session::put('google_userinfo', [
                'email' => $userInfo->email,
                'name' => $userInfo->name,
                'picture' => $userInfo->picture,
            ]);

            $guser = Guser::updateOrCreate(
                ['google_id' => $userInfo->id],
                [
                    'google_id' => $userInfo->id,
                    'google_name' => $userInfo->name,
                    'google_email' => $userInfo->email,
                    'google_picture' => $userInfo->picture,
                    'google_access_token' => json_encode($token),
                    'google_refresh_token' => $token['refresh_token'] ?? null,
                    'google_token_expires_at' => now()->addSeconds($token['expires_in']),
                ]
            );

            return redirect('/#meets');
        }

        return redirect('/')->withErrors('Erreur OAuth');
    }
}
