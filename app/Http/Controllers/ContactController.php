<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message as IlluminateMailMessage;

class ContactController extends Controller
{
    public function sendMessage(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email',
            'message' => 'required',
        ]);

        try {
            // Create a variable for the full name
            $fullName = $validatedData['firstname'] . ' ' . $validatedData['lastname'];

            // Send the email without using a view
            Mail::raw($validatedData['message'], function (IlluminateMailMessage $message) use ($validatedData, $fullName) {
                $message->to('hajji.tas520@gmail.com')
                        ->subject('Nouveau message de ' . $fullName);
            });

            return response()->json(['message' => 'Message envoyé avec succès'], 200);
        } catch (\Exception $e) {
            // Handle the error
            Log::error('Erreur lors de l\'envoi du message : ' . $e->getMessage());

            // Return a JSON response with an error message and additional information for debugging
            return response()->json([
                'error' => 'Erreur lors de l\'envoi du message. Vérifiez les logs pour plus d\'informations.',
                'exception' => $e->getMessage(),
                'trace' => $e->getTrace(),
            ], 500);
        }
    }
}
