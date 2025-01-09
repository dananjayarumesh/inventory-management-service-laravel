<?php

namespace Tests\Unit\Controllers;

use App\Exceptions\ItemQtyNotSufficientException;
use App\Http\Controllers\DispatchNoteController;
use App\Http\Requests\DispatchNotes\ListRequest;
use App\Http\Requests\DispatchNotes\StoreRequest;
use App\Repositories\DispatchNoteRepository;
use App\Repositories\DispatchNoteRepositoryInterface;
use Mockery;
use Tests\TestCase;
use Illuminate\Http\JsonResponse as HttpJsonResponse;
use Illuminate\Support\Facades\Auth;

class DispatchNoteControllerTest extends TestCase
{
    protected $dispatchNoteRepositoryMock;
    protected $controller;

    public function setUp(): void
    {
        parent::setUp();

        $this->dispatchNoteRepositoryMock = Mockery::mock(DispatchNoteRepository::class);
        $this->app->instance(DispatchNoteRepositoryInterface::class, $this->dispatchNoteRepositoryMock);
        $this->controller = app(DispatchNoteController::class);
    }

    public function testGetDispatchNotesList()
    {
        $this->dispatchNoteRepositoryMock
            ->shouldReceive('getPaginatedRecords')
            ->once()
            ->with(1, 12)
            ->andReturn([
                [
                    'id' => 1,
                    'note' => 'note 1'
                ],
                [
                    'id' => 2,
                    'note' => 'note 2'
                ]
            ]);

        $request = Mockery::mock(ListRequest::class)->shouldIgnoreMissing();
        $request->shouldReceive('input')
            ->with('page')
            ->andReturn(1);

        $request->shouldReceive('input')
            ->with('per_page')
            ->andReturn(12);

        $response = $this->controller->index($request);
        $this->assertInstanceOf(HttpJsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($response->getData(true), [
            'page' => 1,
            'per_page' => 12,
            'data' => [
                [
                    'id' => 1,
                    'note' => 'note 1'
                ],
                [
                    'id' => 2,
                    'note' => 'note 2'
                ]
            ]
        ]);
    }

    public function testGetSingleDispatchNote()
    {
        $dispatchNoteId = 1;
        $this->dispatchNoteRepositoryMock
            ->shouldReceive('getSingleRecord')
            ->once()
            ->with($dispatchNoteId)
            ->andReturn([
                'id' => $dispatchNoteId,
                'note' => 'Test note'
            ]);

        $response = $this->controller->show($dispatchNoteId);

        $this->assertInstanceOf(HttpJsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($response->getData(true), [
            'data' => [
                'id' => $dispatchNoteId,
                'note' => 'Test note'
            ]
        ]);
    }

    public function testStoreSuccess()
    {
        $this->dispatchNoteRepositoryMock
            ->shouldReceive('storeRecordWithItemUpdate')
            ->once()
            ->with(1, 'Test Note', 5, 123)
            ->andReturn(true);

        $request = Mockery::mock(StoreRequest::class);
        $request->shouldReceive('input')
            ->with('item_id')
            ->andReturn(1);
        $request->shouldReceive('input')
            ->with('note')
            ->andReturn('Test Note');
        $request->shouldReceive('input')
            ->with('qty')
            ->andReturn(5);

        Auth::shouldReceive('user')
            ->once()
            ->andReturn((object) ['id' => 123]);

        $response = $this->controller->store($request);

        $this->assertInstanceOf(HttpJsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testStoreFailureDueToInsufficientQty()
    {
        $this->dispatchNoteRepositoryMock
            ->shouldReceive('storeRecordWithItemUpdate')
            ->once()
            ->andThrow(ItemQtyNotSufficientException::class);

        $request = Mockery::mock(StoreRequest::class);
        $request->shouldReceive('input')
            ->with('item_id')
            ->andReturn(1);
        $request->shouldReceive('input')
            ->with('note')
            ->andReturn('Test Note');
        $request->shouldReceive('input')
            ->with('qty')
            ->andReturn(5);

        Auth::shouldReceive('user')
            ->once()
            ->andReturn((object) ['id' => 123]);

        $response = $this->controller->store($request);

        $this->assertInstanceOf(HttpJsonResponse::class, $response);
        $this->assertEquals(422, $response->getStatusCode());
    }
}
