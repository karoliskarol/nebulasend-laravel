<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EmailMessage;

class StatisticsController extends Controller
{
    public function __invoke() {
        $like = ['like', '%' . env('EMAIL_END') . '%'];

        return response()->json([
            'users' => \App\Models\User::count(),
            'sent' => EmailMessage::where('sent_by', ...$like)->count(),
            'received' => EmailMessage::where('sent_to', ...$like)->count(),
        ]);
    }
}
