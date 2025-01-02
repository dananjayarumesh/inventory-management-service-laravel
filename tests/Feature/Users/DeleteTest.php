<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\HttpHeaders;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use HttpHeaders, RefreshDatabase, WithFaker;

    public function testDeleteRecord(): void
    {
        $users = User::factory()->count(2)->create();
        $response = $this->delete(
            '/api/users/' . $users[0]->id,
            [],
            $this->getAuthHeaders()
        );
        $response->assertStatus(204);
        $this->assertDatabaseHas('users', [
            'id' => $users[1]['id']
        ]);
        $this->assertDatabaseMissing('users', [
            'id' => $users[0]['id']
        ]);
    }

    public function testDeleteRecordUserCannotDeleteHisOwn(): void
    {
        $response = $this->delete(
            '/api/users/1',
            [],
            $this->getAuthHeaders([
                'id' => 1,
                'role' => 'admin'
            ])
        );
        $response->assertStatus(status: 403);
        $response->assertJson([
            'errors' => 'Cannot delete this user at the moment.'
        ]);
    }

    public function testDeleteRecordOnlyAdminHaveAccess()
    {
        $user = User::factory()->create();
        $response = $this->delete(
            '/api/users/' . $user->id,
            [],
            $this->getAuthHeaders([
                'role' => 'store_keeper'
            ])
        );
        $response->assertStatus(status: 403);
        $response->assertJson([
            'errors' => 'You do not have access to perform this action.'
        ]);
    }
}
