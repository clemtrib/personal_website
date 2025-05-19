<?php

namespace App\Http\Controllers;

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Oauth2;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

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
        $client->setRedirectUri(route('google.callback'));

        if ($request->has('code')) {
            $token = $client->fetchAccessTokenWithAuthCode($request->get('code'));
            Session::put('google_token', $token);


            // Définir le token sur le client
            $client->setAccessToken($token);

            // Récupérer les infos de l'utilisateur
            $oauth2 = new Google_Service_Oauth2($client);
            $userInfo = $oauth2->userinfo->get();

            // Exemple : récupérer l'email
            $email = $userInfo->email;
            Session::put('google_email', $email);

            //return redirect('/dashboard')->with('success', 'Google connecté !');
            return redirect('/');
        }

        return redirect('/')->withErrors('Erreur OAuth');
    }
}
