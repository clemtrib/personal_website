<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MessageController extends Controller
{

    const VALIDATION_RULES = [
        'fullname' => 'required|string|max:255',
        'email' => 'required|email',
        'message' => 'required|string',
        'recaptcha_token' => 'required|string',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $messages = Message::orderBy('created_at', 'desc')->get();
        return response()->json($messages);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => getenv('RECAPTCHA_PRIVATE_KEY') ?? null,
                'response' => $request->recaptcha_token,
                'remoteip' => $request->ip(),
            ]);

            $result = $response->json();

            if (!($result['success'] ?? false) || ($result['score'] ?? 0) < 0.5) {
                return back()->withErrors(['general' => 'Échec de la vérification reCAPTCHA.']);
            }

            $validatedData = $request->validate(self::VALIDATION_RULES);
            $message = new Message();
            $message->fullname = $this->sanitizeInput($validatedData['fullname']);
            $message->email = $this->sanitizeInput($validatedData['email']);
            $message->message = $this->sanitizeInput($validatedData['message']);
            $message->save();
            return redirect()->back()->with('success', 'Message envoyé avec succès');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        return response()->json($message);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Message $message)
    {
        $validatedData = $request->validate(self::VALIDATION_RULES);
        $message->update($validatedData);
        return response()->json(['message' => 'Message mis à jour avec succès']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        $message->delete();
        return response()->json(['message' => 'Message supprimé avec succès']);
    }

    private function sanitizeInput(String $input) : String {
        // Remove <script> blocks
        $input = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $input);
        // Remove other tags
        $input = strip_tags($input);
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }
}
