<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\HttpHeaders;
use Tests\TestCase;

class GetListTest extends TestCase
{
    use HttpHeaders, RefreshDatabase;

    public function testGetList(): void
    {
        $categories = Category::factory()->count(2)->create();

        $response = $this->get(
            '/api/categories',
            $this->getAuthHeaders()
        );

        $response->assertStatus(200);
        $response->assertJson([
            'data' => $categories->toArray()
        ]);
    }
}
