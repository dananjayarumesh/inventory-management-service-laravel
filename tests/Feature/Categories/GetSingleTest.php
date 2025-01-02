<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\HttpHeaders;
use Tests\TestCase;

class GetSingleTest extends TestCase
{
    use HttpHeaders, RefreshDatabase;

    public function testGetSingle(): void
    {
        $categories = Category::factory()->count(3)->create();

        // category 1
        $response = $this->get(
            '/api/categories/' . $categories[0]->id,
            $this->getAuthHeaders()
        );
        $response->assertSuccessful();
        $response->assertJson([
            'data' => $categories[0]->toArray()
        ]);

        // category 2
        $response = $this->get(
            '/api/categories/' . $categories[1]->id,
            $this->getAuthHeaders()
        );
        $response->assertSuccessful();
        $response->assertJson([
            'data' => $categories[1]->toArray()
        ]);
    }

    public function testGetSingleInvalidId(): void
    {
        // check with an invalid id
        $response = $this->get(
            '/api/categories/1',
            $this->getAuthHeaders()
        );
        $response->assertStatus(404);
        $response->assertJson([
            'errors' => 'Resource not found.'
        ]);
    }
}
