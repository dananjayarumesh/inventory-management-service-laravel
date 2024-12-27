<?php

namespace Tests\Feature\Items;

use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\HttpHeaders;
use Tests\TestCase;

class GetListTest extends TestCase
{
    use HttpHeaders;

    public function testGetList(): void
    {
        $items = Item::factory()->count(2)->create();

        $response = $this->get(
            '/api/items',
            $this->getAuthHeaders()
        );

        $response->assertSuccessful();
        $response->assertJson([
            'items' => $items->toArray()
        ]);

        $response->assertStatus(200);
    }
}
