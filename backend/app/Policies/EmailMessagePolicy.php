<?php

namespace App\Policies;

use App\Models\EmailMessage;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EmailMessagePolicy
{
    public function belongsToAuthor(User $user, EmailMessage $emailsMessage) {
        return $user->id === $emailsMessage->user_id;
    }
}