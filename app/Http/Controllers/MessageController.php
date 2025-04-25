<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Exception;
use Illuminate\Http\Request;

class MessageController extends Controller
{
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
            $validatedData = $request->validate([
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'email' => 'required|email',
                'phone' => 'required|max:255',
                'object' => 'required|string|max:255',
                'message' => 'required|string',
            ]);

            $message = new Message();
            $message->firstname = $validatedData['firstname'];
            $message->lastname = $validatedData['lastname'];
            $message->email = $validatedData['email'];
            $message->phone = $validatedData['phone'];
            $message->object = $validatedData['object'];
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
        $validatedData = $request->validate([
            'firstname' => 'sometimes|required|string|max:255',
            'lastname' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email',
            'phone' => 'sometimes|required|max:255',
            'object' => 'sometimes|required|string|max:255',
            'message' => 'sometimes|required|string',
        ]);

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
