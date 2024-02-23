<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmailMessageResource;
use App\Models\EmailMessage;
use App\Services\EmailMessageService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Vincendev\Laraflood\Facade\Laraflood;

class EmailMessageController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $a = $request->input('a');

        EmailMessageService::getSmtpMessages();

        $user = auth()->user();

        $messages = $user->EmailsMessages()->when(
            $search,
            fn($query, $search) => $query->search($search)
        );

        $messages = match ($a) {
            'important' => $messages->where('important', '=', '1'),
            'starred' => $messages->where('starred', '=', '1'),
            'sent' => $messages->where('sent_by', '=', $user->name . env('EMAIL_END')),
            'all' => $messages,
            'trashed' => $messages->onlyTrashed(),
            default => $messages->where('sent_to', '=', $user->name . env('EMAIL_END'))
        };

        return EmailMessageResource::collection(
            $messages->latest()->paginate($perPage = 20)
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sent_to' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        $user = auth()->user();

        if(!Laraflood::check($user->id, 'send-message', 3, 2)) {
            throw ValidationException::withMessages([
                'attempts' => 'You are sending message too often.'
            ]);
        }

        $summary = new \Html2Text\Html2Text($validated['message']);

        EmailMessageService::send($validated['sent_to'], $validated['subject'], $validated['message'], $user);

        EmailMessage::create([
            'user_id' => $user->id,
            'sent_by' => $user->name . env('EMAIL_END'),
            'recipient' => $user->recipient_name,
            'summary' => $summary->getText(),
            ...$validated
        ]);

        return response()->json([
            'message' => 'Message sent successfully.'
        ]);
    }

    public function show(EmailMessage $emailsMessage)
    {
        $this->authorize('belongsToAuthor', $emailsMessage);

        return new EmailMessageResource($emailsMessage);
    }

    public function update(string $emailsMessage)
    {
        $emailMessage = EmailMessage::findOrFail($emailsMessage);

        $this->authorize('belongsToAuthor', $emailMessage);

        $emailMessage->starred = !$emailMessage->starred;
        $emailMessage->save();

        return $emailMessage;
    }

    public function destroy(string $emailsMessage)
    {
        $trashed = EmailMessage::onlyTrashed()->findOrFail($emailsMessage);

        $this->authorize('belongsToAuthor', $trashed);

        $trashed->forceDelete();

        return response(status: 204);
    }

    public function softDestroy(EmailMessage $emailsMessage)
    {
        $this->authorize('belongsToAuthor', $emailsMessage);

        $emailsMessage->delete();

        return response(status: 204);
    }

    public function restore(string $emailsMessage)
    {
        $trashed = EmailMessage::onlyTrashed()->findOrFail($emailsMessage);

        $this->authorize('belongsToAuthor', $trashed);

        $trashed->restore();

        return response()->json([
            'message' => 'Email message restored successfully.'
        ]);
    }
}
