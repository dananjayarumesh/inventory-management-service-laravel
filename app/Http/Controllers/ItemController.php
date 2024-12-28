<?php

namespace App\Http\Controllers;

use App\Http\Requests\Items\ListRequest;
use App\Http\Responses\JsonResponse;
use App\Repositories\ItemRepositoryInterface;

class ItemController extends Controller
{
    protected $itemRepository;

    public function __construct(ItemRepositoryInterface $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    public function index(ListRequest $request)
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

    public function show($id)
    {
        return JsonResponse::view(
            $this->itemRepository->getSingleRecord($id)
        );
    }
}
