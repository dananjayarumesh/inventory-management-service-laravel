<?php

namespace Tests\Feature\Items;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\HttpHeaders;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use HttpHeaders, RefreshDatabase, WithFaker;

    public function testCreateRecord(): void
    {
        $category = Category::factory()->create();
        $request = [
            'name' => $this->faker->name,
            'category_id' => $category->id
        ];
        $response = $this->post(
            '/api/items',
            $request,
            $this->getAuthHeaders([
                'id' => 100
            ])
        );
        $response->assertStatus(201);
        $response->assertJson([
            'success' => true
        ]);
        $this->assertDatabaseHas('items', [
            'name' => $request['name'],
            'category_id' => $category->id,
            'created_by' => 100
        ]);
    }

    public function testCreateRecordValidations(): void
    {
        $request = [];
        $response = $this->post(
            '/api/items',
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
        $request = [
            'name' => $this->faker->name,
            'category_id' => 2 // invalid category id
        ];
        $response = $this->post(
            '/api/items',
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
}
