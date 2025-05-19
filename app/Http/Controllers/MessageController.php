<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
        $messages = Message::orderBy('created_at', 'desc')->paginate(10);

        return Inertia::render('Messages', [
            'messages' => $messages,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            if (!$this->verifyCaptcha($request)) {
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

        try {
            $message->update($validatedData);
            return to_route('messages', ['json' => false])->with('success', 'Message modifié avec succès');
        } catch (\Exception $e) {
            return back()->with('error',  $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        try {
            $message->delete();
            return to_route('messages', ['json' => false])->with('success', 'Message supprimé  avec succès');
        } catch (\Exception $e) {
            return back()->with('error',  $e->getMessage());
        }
    }

    /**
     * Sanitize input
     */
    private function sanitizeInput(string $input): string
    {
        // Remove <script> blocks
        $input = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $input);
        // Remove other tags
        $input = strip_tags($input);
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }
}
