<?php 

namespace Tests\Feature\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

trait HttpHeaders
{
    private function getAccessToken($payload = [])
    {
        $user = User::factory()->create($payload);
        return JWTAuth::fromUser($user);
    }

    public function getAuthHeaders($payload = [], $token = null)
    {
        return [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . ($token ?? $this->getAccessToken($payload))
        ];
    }
}
