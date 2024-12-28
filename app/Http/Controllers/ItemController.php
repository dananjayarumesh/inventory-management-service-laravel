<?php

namespace App\Http\Controllers;

use App\Http\Requests\Items\ListRequest;
use App\Repositories\ItemRepositoryInterface;
use App\Util\Response;

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

        return Response::paginate(
            $this->itemRepository->getPaginatedRecords(
                $page,
                $perPage
            ),
            $page,
            $perPage
        );
    }
}
