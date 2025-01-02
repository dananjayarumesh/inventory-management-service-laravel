<?php

namespace Tests\Feature\DispatchNotes;

use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\HttpHeaders;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use HttpHeaders, RefreshDatabase, WithFaker;

    public function testCreateRecord(): void
    {
        $item = Item::factory()->create([
            'qty' => 20
        ]);
        $request = [
            'note' => substr($this->faker->name, 0, 100),
            'qty' => 19,
            'item_id' => $item->id
        ];
        $response = $this->post(
            '/api/dispatch-notes',
            $request,
            $this->getAuthHeaders()
        );
        $response->assertStatus(201);
        $response->assertJson([
            'success' => true
        ]);
        $this->assertDatabaseHas('dispatch_notes', [
            'note' => $request['note'],
            'qty' => $request['qty'],
            'item_id' => $request['item_id'],
        ]);
        // asserts that qty is reduced from the item
        $this->assertDatabaseHas('items', [
            'id' => $request['item_id'],
            'qty' => 1
        ]);
    }

    public function testCreateRecordValidatesRemainingQty(): void
    {
        $item = Item::factory()->create([
            'qty' => 5
        ]);
        $request = [
            'note' => substr($this->faker->name, 0, 100),
            'qty' => 6,
            'item_id' => $item->id
        ];
        $response = $this->post(
            '/api/dispatch-notes',
            $request,
            $this->getAuthHeaders()
        );
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [
                'qty' => [
                    0 => 'Insufficient remaining quantity. Please reduce your input.'
                ]
            ]
        ]);
    }

    public function testCreateRecordValidations(): void
    {
        $request = [];
        $response = $this->post(
            '/api/dispatch-notes',
            $request,
            $this->getAuthHeaders()
        );
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [
                'note' => [
                    0 => 'The note field is required.'
                ],
                'qty' => [
                    0 => 'The qty field is required.'
                ],
                'item_id' => [
                    0 => 'The item id field is required.'
                ]
            ]
        ]);
    }
}
