<?php

namespace Tests\Feature\ReceiveNotes;

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
            'qty' => 5
        ]);
        $request = [
            'note' => substr($this->faker->name, 0, 100),
            'qty' => 19,
            'item_id' => $item->id
        ];
        $response = $this->post(
            '/api/receive-notes',
            $request,
            $this->getAuthHeaders()
        );
        $response->assertStatus(201);
        $response->assertJson([
            'success' => true
        ]);
        $this->assertDatabaseHas('receive_notes', [
            'note' => $request['note'],
            'qty' => $request['qty'],
            'item_id' => $request['item_id'],
        ]);
        // asserts that qty is added to the item
        $this->assertDatabaseHas('items', [
            'id' => $request['item_id'],
            'qty' => 24
        ]);
    }

    public function testCreateRecordValidations(): void
    {
        $request = [];
        $response = $this->post(
            '/api/receive-notes',
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
