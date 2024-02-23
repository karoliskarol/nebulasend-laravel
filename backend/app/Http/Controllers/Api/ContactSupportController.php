<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EmailMessage;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Vincendev\Laraflood\Facade\Laraflood;

class ContactSupportController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'recipient' => 'required|string|min:2|max:255',
            'sent_by' => 'required|email',
            'message' => 'required|string'
        ]);

        if(!Laraflood::checkOnly('ip', 'contact', 2)) {
            throw ValidationException::withMessages([
                'attempts' => 'Too many attempts.'
            ]);
        }

        $message = $validated['message'];

        EmailMessage::create([
            ...$validated,
            'user_id' => env('CONTACT_ACCOUNT_ID'),
            'sent_to' => 'candelario@nebulasend.com',
            'subject' => 'Contact form',
            'summary' => strlen($message) >= 100 ? substr($message, 0, 100) : $message,
        ]);

        Laraflood::addAttempt('ip', 'contact', 1440);

        return response()->json([
            'message' => 'Message was sent successfully.'
        ]);
    }
}
