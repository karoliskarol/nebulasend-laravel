<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmailMessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sent_by' => $this->sent_by,
            'sent_to' => $this->sent_to,
            'subject' => $this->subject,
            'recipient' => $this->recipient,
            'message' => $this->message,
            'summary' => $this->summary,
            'starred' => $this->starred,
            'trash' => $this->trash,
            'important' => $this->important,
            'sent_at' => $this->sent_at,
            'created_at' => $this->created_at,
            'user_id' => $this->user_id,
        ];
    }
}
