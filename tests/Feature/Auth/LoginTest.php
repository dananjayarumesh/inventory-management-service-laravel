<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\Feature\Traits\HttpHeaders;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use HttpHeaders, RefreshDatabase, WithFaker;

    public function testLogin(): void
    {
        $request = [
            'email' => 'admin@inventory.com',
            'password' => 123456
        ];

        User::factory()->create([
            'email' => $request['email'],
            'password' => Hash::make($request['password'])
        ]);

        $response = $this->post(
            '/api/auth/login',
            $request,
            $this->getGuestHeaders()
        );
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'access_token'
        ]);
    }

    public function testLoginValidations(): void
    {
        $request = [];
        $response = $this->post(
            '/api/auth/login',
            $request,
            $this->getGuestHeaders()
        );
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [
                'email' => [
                    0 => 'The email field is required.'
                ],
                'password' => [
                    0 => 'The password field is required.'
                ]
            ]
        ]);
    }

    public function testLoginInvalidCredentials(): void
    {
        $request = [
            'email' => 'admin@inventory.com',
            'password' => 123456
        ];

        User::factory()->create([
            'email' => $request['email'],
            'password' => Hash::make(111111) // different pass
        ]);

        $response = $this->post(
            '/api/auth/login',
            $request,
            $this->getGuestHeaders()
        );
        $response->assertStatus(status: 401);
        $response->assertJson([
            'error' =>  'Invaid credentials provided.'
        ]);
    }
}
