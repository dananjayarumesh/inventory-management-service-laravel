<?php

namespace Tests\Feature\Items;

use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\HttpHeaders;
use Tests\TestCase;

class GetSingleTest extends TestCase
{
    use HttpHeaders, RefreshDatabase;

    public function testGetSingle(): void
    {
        $items = Item::factory()->count(3)->create();

        // item 1
        $response = $this->get(
            '/api/items/' . $items[0]->id,
            $this->getAuthHeaders()
        );
        $response->assertSuccessful();
        $response->assertJson([
            'item' => $items[0]->toArray()
        ]);

        // item 2
        $response = $this->get(
            '/api/items/' . $items[1]->id,
            $this->getAuthHeaders()
        );
        $response->assertSuccessful();
        $response->assertJson([
            'item' => $items[1]->toArray()
        ]);
    }

    public function testGetSingleInvalidId(): void
    {
        // check with an invalid id
        $response = $this->get(
            '/api/items/1',
            $this->getAuthHeaders()
        );
        $response->assertStatus(404);
        $response->assertJson([
            'errors' => 'Resource not found.'
        ]);
    }
}
