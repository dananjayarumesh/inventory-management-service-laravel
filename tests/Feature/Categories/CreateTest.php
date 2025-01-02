<?php

namespace Tests\Feature\Categories;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\HttpHeaders;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use HttpHeaders;
    use RefreshDatabase;
    use WithFaker;

    public function testCreateRecord(): void
    {
        $request = [
            'name' => substr($this->faker->name, 0, 20)
        ];
        $response = $this->post(
            '/api/categories',
            $request,
            $this->getAuthHeaders()
        );
        $response->assertStatus(201);
        $response->assertJson([
            'success' => true
        ]);
        $this->assertDatabaseHas('categories', [
            'name' => $request['name']
        ]);
    }

    public function testCreateRecordValidations(): void
    {
        $request = [];
        $response = $this->post(
            '/api/categories',
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
}
