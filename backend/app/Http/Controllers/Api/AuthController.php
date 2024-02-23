<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Validation\ValidationException;
use Vincendev\Laraflood\Facade\Laraflood;

class AuthController extends Controller
{
    public function user (Request $request) {
        return $request->user();
    }

    public function registration(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:30|unique:users,name|regex:/^[a-z0-9A-Z]+$/u',
            'password' => 'required|string|min:8|max:255|confirmed|regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[\W]).+$/',
            'g-recaptcha-response' => 'required'
        ]);

        AuthService::validateRecaptcha($validated['g-recaptcha-response']);

        $user = User::create([
            'name' => strtolower($validated['name']),
            'password' => bcrypt($validated['password'])
        ]);

        AuthService::createEmailAccount($validated['name'], $user->email_pass);

        return response()->json([
            'message' => 'Account was created succesfully.'
        ]);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'password' => 'required'
        ]);

        if(!Laraflood::check($identity = 'ip', $action = 'login', $maxAttempts = 6)) {
            throw ValidationException::withMessages([
                'attempts' => 'Too many login attempts.'
            ]);
        }

        if(!Auth::attempt($validated)) {
            throw ValidationException::withMessages([
                'name' => 'The credentials you provided are invalid. Please check and try again.'
            ]);
        }

        $token = auth()->user()->createToken('token')->plainTextToken;
        $cookie = Cookie::make('token', $token, 60 * 24, null, null, false, false);

        return response()->json([
            'message' => 'Success.',
            'token' => $token
        ])->withCookie($cookie);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        cookie()->forget('token');

        return response()->json([
            'message' => 'Success.'
        ]);
    }

    public function logoutFromAllDevices(Request $request) {
        auth()->user()->tokens()->delete();

        cookie()->forget('token');

        return response()->json([
            'message' => 'Success.'
        ]);
    }
}
