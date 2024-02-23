<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SettingsController extends Controller
{
    public function updatePassword(Request $request) {
        $validated = $request->validate([
            'opassword' => 'required|string|different:password',
            'password' => 'required|string|min:8|max:255|confirmed|regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[\W]).+$/'
        ]);

        if(!password_verify($validated['opassword'], auth()->user()->password)) {
            throw ValidationException::withMessages([
                'password' => 'Current password is wrong.'
            ]);
        }

        $user = User::find(auth()->id());
        $user->password = bcrypt($validated['password']);
        $user->save();

        return response()->json([
            'message' => 'Password changed successfully.'
        ]);
    }

    public function updateRecipient (Request $request) {
        User::find(auth()->id())->update($request->validate([
            'recipient_name' => 'required|string|min:3|max:255|unique:users,name|unique:users,recipient_name|regex:/^[a-z0-9A-Z]+$/u'
        ]));

        return response()->json([
            'message' => 'Recipient name changed successfully.'
        ]);
    }
}
