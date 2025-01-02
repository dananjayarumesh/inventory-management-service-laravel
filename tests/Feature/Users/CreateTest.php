<?php

namespace Tests\Feature\Users;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\Feature\Traits\HttpHeaders;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use HttpHeaders;
    use RefreshDatabase;
    use WithFaker;

    public function testCreateRecord(): void
    {
        $request = [
            'name' => substr($this->faker->name, 0, 20),
            'email' => $this->faker->email(),
            'password' => '123456',
            'role' => 'store_keeper'
        ];
        $response = $this->post(
            '/api/users',
            $request,
            $this->getAuthHeaders()
        );
        $response->assertStatus(201);
        $response->assertJson([
            'success' => true
        ]);
        $this->assertDatabaseHas('users', [
            'name' => $request['name'],
            'email' => $request['email'],
            'role' => $request['role'],
        ]);
    }

    public function testCreateRecordValidations(): void
    {
        $request = [];
        $response = $this->post(
            '/api/users',
            $request,
            $this->getAuthHeaders()
        );
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [
                'name' => [
                    0 => 'The name field is required.'
                ],
                'email' => [
                    0 => 'The email field is required.'
                ],
                'role' => [
                    0 => 'The role field is required.'
                ],
                'password' => [
                    0 => 'The password field is required.'
                ]
            ]
        ]);
    }

    public function testCreateRecordOnlyAdminHaveAccess()
    {
        $request = [
            'name' => substr($this->faker->name, 0, 20),
            'email' => $this->faker->email(),
            'role' => 'store_keeper'
        ];
        $response = $this->post(
            '/api/users/',
            $request,
            $this->getAuthHeaders([
                'role' => 'store_keeper'
            ])
        );
        $response->assertStatus(status: 403);
        $response->assertJson([
            'error' => 'You do not have access to perform this action.'
        ]);
    }
}
