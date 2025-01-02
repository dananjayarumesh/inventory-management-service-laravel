<?php

namespace App\Http\Controllers;

use App\Exceptions\ItemQtyNotSufficientException;
use App\Http\Requests\DispatchNotes\ListRequest;
use App\Http\Requests\DispatchNotes\StoreRequest;
use App\Http\Responses\JsonResponse;
use App\Repositories\DispatchNoteRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse as HttpJsonResponse;
use Illuminate\Support\Facades\Auth;

class DispatchNoteController extends Controller
{
    protected $dispatchNoteRepository;

    public function __construct(DispatchNoteRepositoryInterface $dispatchNoteRepository)
    {
        $this->dispatchNoteRepository = $dispatchNoteRepository;
    }

    public function index(ListRequest $request): HttpJsonResponse
    {
        $page = $request->page ?? 1;
        $perPage = $request->per_page ?? 10;

        return JsonResponse::paginate(
            $this->dispatchNoteRepository->getPaginatedRecords(
                $page,
                $perPage
            ),
            $page,
            $perPage
        );
    }

    public function show($id): HttpJsonResponse
    {
        return JsonResponse::success(
            $this->dispatchNoteRepository->getSingleRecord($id)
        );
    }

    public function store(StoreRequest $request): HttpJsonResponse
    {
        try {
            $this->dispatchNoteRepository->storeRecordWithItemUpdate(
                $request->item_id,
                $request->note,
                $request->qty,
                Auth::user()->id
            );
            return JsonResponse::created();
        } catch (ItemQtyNotSufficientException $exception) {
            return JsonResponse::validation([
                'qty' => [
                    'Insufficient remaining quantity. Please reduce your input.'
                ]
            ]);
        }
    }
}
