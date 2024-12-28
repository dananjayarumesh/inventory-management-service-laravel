<?php

namespace Tests\Feature\Items;

use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Traits\HttpHeaders;
use Tests\TestCase;

class GetListTest extends TestCase
{
    use HttpHeaders, RefreshDatabase;

    public function testGetList(): void
    {
        $items = Item::factory()->count(2)->create();

        $response = $this->get(
            '/api/items',
            $this->getAuthHeaders()
        );

        $response->assertSuccessful();
        $response->assertJson([
            'page' => 1,
            'per_page' => 10,
            'data' => $items->toArray()
        ]);
    }

    public function testGetListPagination(): void
    {
        $items = Item::factory()->count(10)->create();

        // check page 1 results
        $response = $this->get(
            '/api/items?page=1&per_page=2',
            $this->getAuthHeaders()
        );
        $response->assertSuccessful();
        $response->assertJson([
            'page' => 1,
            'per_page' => 2,
            'data' => array_slice($items->toArray(), 0, 2)
        ]);

        // check page 2 results
        $response = $this->get(
            '/api/items?page=2&per_page=2',
            $this->getAuthHeaders()
        );
        $response->assertSuccessful();
        $response->assertJson([
            'page' => 2,
            'per_page' => 2,
            'data' => array_slice($items->toArray(), 2, 2)
        ]);
    }

    public function testGetListValidation(): void
    {
        $response = $this->get(
            '/api/items?page=test&per_page=test',
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
