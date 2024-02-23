<?php

namespace App\Services;

use Illuminate\Validation\ValidationException;

class AuthService
{
    public static function createEmailAccount(string $name, string $emailPassRandom)
    {
        $emailPassword = env('EMAIL_PEPPER') . $emailPassRandom;
        $emailQuota = 100;

        $post_fields = array(
            'email' => $name,
            'password' => $emailPassword,
            'quota' => $emailQuota
        );

        $context = stream_context_create(
            array(
                'http' => array(
                    'method' => 'POST',
                    'header' => "Content-Type: application/x-www-form-urlencoded\r\nAuthorization: cpanel " . env('CP_USER') . ":" . env('CP_API_KEY') . "\n",
                    'content' => http_build_query($post_fields),
                ),
            )
        );

        file_get_contents(env('CP_API_URL') . 'execute/Email/add_pop', false, $context);
    }

    public static function validateRecaptcha($recaptcha)
    {
        $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . env('RECAPTCHA_SECRET') . '&response=' . $recaptcha;
        $response = json_decode(file_get_contents($url));

        if (!$response->success) {
            throw ValidationException::withMessages([
                'recaptcha' => 'Please verify the recaptcha.'
            ]);
        }
    }
}

?>