<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReceiveNotes\ListRequest;
use App\Http\Requests\ReceiveNotes\StoreRequest;
use App\Http\Responses\JsonResponse;
use App\Repositories\ReceiveNoteRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse as HttpJsonResponse;
use Illuminate\Support\Facades\Auth;

class ReceiveNoteController extends Controller
{
    protected $receiveNoteRepository;

    public function __construct(ReceiveNoteRepositoryInterface $receiveNoteRepository)
    {
        $this->receiveNoteRepository = $receiveNoteRepository;
    }

    public function index(ListRequest $request): HttpJsonResponse
    {
        $page = $request->page ?? 1;
        $perPage = $request->per_page ?? 10;

        return JsonResponse::paginate(
            $this->receiveNoteRepository->getPaginatedRecords(
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
            $this->receiveNoteRepository->getSingleRecord($id)
        );
    }

    public function store(StoreRequest $request): HttpJsonResponse
    {
        $this->receiveNoteRepository->storeRecordWithItemUpdate(
            $request->item_id,
            $request->note,
            $request->qty,
            Auth::user()->id
        );
        return JsonResponse::created();
    }
}
