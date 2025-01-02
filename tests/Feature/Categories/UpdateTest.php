<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\HttpHeaders;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use HttpHeaders, RefreshDatabase, WithFaker;

    public function testUpdateRecord() {
        $category = Category::factory()->create();
        $request = [
            'name' => substr($this->faker->name, 0, 20)
        ];
        $response = $this->put(
            '/api/categories/' . $category->id,
            $request,
            $this->getAuthHeaders()
        );
        $response->assertStatus(204);
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => $request['name']
        ]);
    }

    public function testUpdateRecordValidations(): void
    {
        $category = Category::factory()->create();
        $request = [];
        $response = $this->put(
            '/api/categories/' . $category->id,
            $request,
            $this->getAuthHeaders()
        );
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [
                'name' => [
                    0 => 'The name field is required.'
                ]
            ]
        ]);
    }

    public function testUpdateRecordInvalidCategoryId(): void
    {
        $request = [
            'name' => substr($this->faker->name, 0, 20)
        ];
        $response = $this->put(
            '/api/categories/32', // invalid id
            $request,
            $this->getAuthHeaders()
        );
        $response->assertStatus(status: 404);
        $response->assertJson([
            'errors' => 'Resource not found.'
        ]);
    }
}
