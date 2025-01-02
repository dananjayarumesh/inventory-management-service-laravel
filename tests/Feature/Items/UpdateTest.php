<?php

namespace Tests\Feature\Items;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\HttpHeaders;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use HttpHeaders, RefreshDatabase, WithFaker;

    public function testUpdateRecord() {
        $category = Category::factory()->create();
        $item = Item::factory()->create();
        $request = [
            'name' => $this->faker->name,
            'category_id' => $category->id
        ];
        $response = $this->put(
            '/api/items/' . $item->id,
            $request,
            $this->getAuthHeaders()
        );
        $response->assertStatus(204);
        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'name' => $request['name'],
            'category_id' => $category->id
        ]);
    }

    public function testUpdateRecordValidations(): void
    {
        $item = Item::factory()->create();
        $request = [];
        $response = $this->put(
            '/api/items/' . $item->id,
            $request,
            $this->getAuthHeaders()
        );
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [
                'name' => [
                    0 => 'The name field is required.'
                ],
                'category_id' => [
                    0 => 'The category id field is required.'
                ],
            ]
        ]);
    }

    public function testRecordInvalidCategoryId(): void
    {
        $item = Item::factory()->create();
        $request = [
            'name' => $this->faker->name,
            'category_id' => 2 // invalid category id
        ];
        $response = $this->put(
            '/api/items/' . $item->id,
            $request,
            $this->getAuthHeaders()
        );
        $response->assertStatus(status: 422);
        $response->assertJson([
            'errors' => [
                'category_id' => [
                    0 => 'The selected category id is invalid.'
                ],
            ]
        ]);
    }

    public function testRecordInvalidItemId(): void
    {
        $category = Category::factory()->create();
        $request = [
            'name' => $this->faker->name,
            'category_id' => $category->id 
        ];
        $response = $this->put(
            '/api/items/32', // invalid id
            $request,
            $this->getAuthHeaders()
        );
        $response->assertStatus(status: 404);
        $response->assertJson([
            'errors' => 'Resource not found.'
        ]);
    }
}
