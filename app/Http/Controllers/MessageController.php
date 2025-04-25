<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Exception;
use Illuminate\Http\Request;

class MessageController extends Controller
{

    const VALIDATION_RULES = [
        'fullname' => 'required|string|max:255',
        'email' => 'required|email',
        'message' => 'required|string',
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
            $validatedData = $request->validate(self::VALIDATION_RULES);
            $message = new Message();
            $message->fullname = $validatedData['fullname'];
            $message->email = $validatedData['email'];
            $message->message = $validatedData['message'];
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
}
