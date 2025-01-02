<?php

namespace App\Services;

use App\Exceptions\AuthFailedException;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService implements AuthServiceInterface
{
    public function getAccessTokenByCred(string $email, string $password): string
    {
        if (
            !$accessToken = Auth::attempt(
                compact(
                    'email',
                    'password'
                )
            )
        ) {
            throw new AuthFailedException('Invaid credentials provided.');
        }
        return $accessToken;
    }
}
