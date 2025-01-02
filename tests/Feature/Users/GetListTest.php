<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Tests\Feature\Traits\HttpHeaders;
use Tests\TestCase;

class GetListTest extends TestCase
{
    use HttpHeaders;
    use RefreshDatabase;

    public function testGetList(): void
    {
        $users = User::factory()->count(2)->create();
        $response = $this->get(
            '/api/users',
            $this->getAuthHeaders()
        );

        $response->assertStatus(200);
        $response->assertJson([
            'data' => $users->toArray()
        ]);
    }

    public function testCreateRecordOnlyAdminHaveAccess()
    {
        User::factory()->count(2)->create();

        $response = $this->get(
            '/api/users',
            $this->getAuthHeaders([
                'id' => 3,
                'role' => 'store_keeper'
            ])
        );

        $response->assertStatus(status: 403);
        $response->assertJson([
            'error' => 'You do not have access to perform this action.'
        ]);
    }
}
