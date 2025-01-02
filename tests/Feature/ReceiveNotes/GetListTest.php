<?php

namespace Tests\Feature\ReceiveNotes;

use App\Models\ReceiveNote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\HttpHeaders;
use Tests\TestCase;

class GetListTest extends TestCase
{
    use HttpHeaders, RefreshDatabase;

    public function testGetList(): void
    {
        $receiveNotes = ReceiveNote::factory()->count(2)->create();

        $response = $this->get(
            '/api/receive_notes',
            $this->getAuthHeaders()
        );

        $response->assertSuccessful();
        $response->assertJson([
            'page' => 1,
            'per_page' => 10,
            'data' => $receiveNotes->toArray()
        ]);
    }

    public function testGetListPagination(): void
    {
        $receiveNotes = ReceiveNote::factory()->count(10)->create();

        // check page 1 results
        $response = $this->get(
            '/api/receive_notes?page=1&per_page=2',
            $this->getAuthHeaders()
        );
        $response->assertSuccessful();
        $response->assertJson([
            'page' => 1,
            'per_page' => 2,
            'data' => array_slice($receiveNotes->toArray(), 0, 2)
        ]);

        // check page 2 results
        $response = $this->get(
            '/api/receive_notes?page=2&per_page=2',
            $this->getAuthHeaders()
        );
        $response->assertSuccessful();
        $response->assertJson([
            'page' => 2,
            'per_page' => 2,
            'data' => array_slice($receiveNotes->toArray(), 2, 2)
        ]);
    }

    public function testGetListValidation(): void
    {
        $response = $this->get(
            '/api/receive_notes?page=test&per_page=test',
            $this->getAuthHeaders()
        );
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [
                'page' => [
                    0 => 'The page field must be a number.'
                ],
                'per_page' => [
                    0 => 'The per page field must be a number.'
                ],
            ]
        ]);
    }
}
