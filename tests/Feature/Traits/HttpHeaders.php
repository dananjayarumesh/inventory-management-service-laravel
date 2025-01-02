<?php

namespace Tests\Feature\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

trait HttpHeaders
{
    protected $authUser;
    private function getAccessToken($payload = []): mixed
    {
        $user = User::factory()->create($payload);
        return JWTAuth::fromUser($user);
    }

    public function getAuthHeaders($payload = [], $token = null): array
    {
        return [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . ($token ?? $this->getAccessToken($payload))
        ];
    }

    public function getGuestHeaders(): array
    {
        return [
            'Accept' => 'application/json'
        ];
    }
}
