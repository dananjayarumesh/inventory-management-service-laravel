<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\HttpHeaders;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use HttpHeaders, RefreshDatabase, WithFaker;

    public function testUpdateRecord()
    {
        $user = User::factory()->create([
            'role' => 'admin'
        ]);
        $request = [
            'name' => substr($this->faker->name, 0, 20),
            'role' => 'store_keeper'
        ];
        $response = $this->put(
            '/api/users/' . $user->id,
            $request,
            $this->getAuthHeaders([
                'role' => 'admin'
            ])
        );
        $response->assertStatus(status: 204);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $request['name'],
            'role' => $request['store_keeper'],
        ]);
    }

    public function testUpdateRecordValidations(): void
    {
        $user = User::factory()->create();
        $request = [];
        $response = $this->put(
            '/api/users/' . $user->id,
            $request,
            $this->getAuthHeaders([
                'role' => 'admin'
            ])
        );
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [
                'name' => [
                    0 => 'The name field is required.'
                ],
                'email' => [
                    0 => 'The email field is required.'
                ]
            ]
        ]);
    }

    public function testUpdateRecordInvalidUserId(): void
    {
        $request = [
            'name' => substr($this->faker->name, 0, 20),
            'role' => 'admin'
        ];
        $response = $this->put(
            '/api/users/32', // invalid id
            $request,
            $this->getAuthHeaders([
                'role' => 'admin'
            ])
        );
        $response->assertStatus(status: 404);
        $response->assertJson([
            'errors' => 'Resource not found.'
        ]);
    }

    public function testUpdateRecordOnlyAdminCanUpdate()
    {
        $user = User::factory()->create([
            'role' => 'admin'
        ]);
        $request = [
            'name' => substr($this->faker->name, 0, 20),
            'role' => 'store_keeper'
        ];
        $response = $this->put(
            '/api/users/' . $user->id,
            $request,
            $this->getAuthHeaders([
                'role' => 'store_keeper'
            ])
        );
        $response->assertStatus(status: 403);
        $response->assertJson([
            'errors' => 'You do not have access to perform this action.'
        ]);
    }

    public function testUpdateRecordAuthUserCannotChangeRole()
    {
        $request = [
            'name' => substr($this->faker->name, 0, 20),
            'role' => 'store_keeper'
        ];
        $authUserId = 1;
        $response = $this->put(
            '/api/users/' . $authUserId,
            $request,
            $this->getAuthHeaders([
                'id' => $authUserId,
                'role' => 'admin'
            ])
        );
        $response->assertStatus(status: 403);
        $response->assertJson([
            'errors' => 'You cannot update your own role.'
        ]);
    }
}
