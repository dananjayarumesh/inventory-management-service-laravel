<?php

namespace Tests\Feature\ReceiveNotes;

use App\Models\ReceiveNote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\HttpHeaders;
use Tests\TestCase;

class GetSingleTest extends TestCase
{
    use HttpHeaders;
    use RefreshDatabase;

    public function testGetSingle(): void
    {
        $receiveNotes = ReceiveNote::factory()->count(3)->create();

        // item 1
        $response = $this->get(
            '/api/receive-notes/' . $receiveNotes[0]->id,
            $this->getAuthHeaders()
        );
        $response->assertSuccessful();
        $response->assertJson([
            'data' => $receiveNotes[0]->toArray()
        ]);

        // item 2
        $response = $this->get(
            '/api/receive-notes/' . $receiveNotes[1]->id,
            $this->getAuthHeaders()
        );
        $response->assertSuccessful();
        $response->assertJson([
            'data' => $receiveNotes[1]->toArray()
        ]);
    }

    public function testGetSingleInvalidId(): void
    {
        // check with an invalid id
        $response = $this->get(
            '/api/receive-notes/1',
            $this->getAuthHeaders()
        );
        $response->assertStatus(404);
        $response->assertJson([
            'errors' => 'Resource not found.'
        ]);
    }
}
