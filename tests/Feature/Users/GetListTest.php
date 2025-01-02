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
    use HttpHeaders, RefreshDatabase;

    public function testGetList(): void
    {
        $users = User::factory()->count(2)->create();

        $response = $this->get(
            '/api/users',
            $this->getAuthHeaders([
                'id' => 3
            ])
        );

        $authUser = User::find(3);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => array_merge(
                $users->toArray(),
                $authUser->toArray()
            )
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
            'errors' => 'You do not have access to perform this action.'
        ]);
    }
}
