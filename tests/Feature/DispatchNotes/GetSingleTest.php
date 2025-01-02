<?php

namespace Tests\Feature\DispatchNotes;

use App\Models\DispatchNote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\HttpHeaders;
use Tests\TestCase;

class GetSingleTest extends TestCase
{
    use HttpHeaders, RefreshDatabase;

    public function testGetSingle(): void
    {
        $dispatchNotes = DispatchNote::factory()->count(3)->create();

        // item 1
        $response = $this->get(
            '/api/dispatch_notes/' . $dispatchNotes[0]->id,
            $this->getAuthHeaders()
        );
        $response->assertSuccessful();
        $response->assertJson([
            'data' => $dispatchNotes[0]->toArray()
        ]);

        // item 2
        $response = $this->get(
            '/api/dispatch_notes/' . $dispatchNotes[1]->id,
            $this->getAuthHeaders()
        );
        $response->assertSuccessful();
        $response->assertJson([
            'data' => $dispatchNotes[1]->toArray()
        ]);
    }

    public function testGetSingleInvalidId(): void
    {
        // check with an invalid id
        $response = $this->get(
            '/api/dispatch_notes/1',
            $this->getAuthHeaders()
        );
        $response->assertStatus(404);
        $response->assertJson([
            'errors' => 'Resource not found.'
        ]);
    }
}
