<?php

namespace App\Services;

use App\Exceptions\AuthFailedException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService implements AuthServiceInterface
{
    public function getAccessTokenByCred(string $email, string $password): string
    {
        $token = auth('api')->attempt(compact(
            'email',
            'password'
        ));

        if (!$token) {
            throw new AuthFailedException('Invaid credentials provided.');
        }

        return $token;
    }
}
