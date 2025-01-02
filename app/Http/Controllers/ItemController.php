<?php

namespace App\Http\Controllers;

use App\Http\Requests\Items\ListRequest;
use App\Http\Requests\Items\StoreRequest;
use App\Http\Requests\Items\UpdateRequest;
use App\Http\Responses\JsonResponse;
use App\Repositories\ItemRepositoryInterface;
use Illuminate\Http\JsonResponse as HttpJsonResponse;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    protected $itemRepository;

    public function __construct(ItemRepositoryInterface $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    public function index(ListRequest $request): HttpJsonResponse
    {
        $page = $request->page ?? 1;
        $perPage = $request->per_page ?? 10;

        return JsonResponse::paginate(
            $this->itemRepository->getPaginatedRecords(
                $page,
                $perPage
            ),
            $page,
            $perPage
        );
    }

    public function show($id): HttpJsonResponse
    {
        return JsonResponse::view(
            $this->itemRepository->getSingleRecord($id)
        );
    }

    public function store(StoreRequest $request): HttpJsonResponse
    {
        $this->itemRepository->storeRecord(
            $request->name,
            $request->category_id,
            Auth::user()->id
        );
        return JsonResponse::created();
    }

    public function update($id, UpdateRequest $request): HttpJsonResponse
    {
        $this->itemRepository->updateRecord(
            $id,
            $request->name,
            $request->category_id
        );
        return JsonResponse::updated();
    }
}
