<?php

namespace App\Http\Controllers;

use App\Http\Requests\Categories\StoreRequest;
use App\Http\Requests\Categories\UpdateRequest;
use App\Repositories\CategoryRepositoryInterface;
use Illuminate\Http\JsonResponse as HttpJsonResponse;
use App\Http\Responses\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index(): HttpJsonResponse
    {
        return JsonResponse::success(
            $this->categoryRepository->getRecords()
        );
    }

    public function show($id): HttpJsonResponse
    {
        return JsonResponse::success(
            $this->categoryRepository->getSingleRecord($id)
        );
    }

    public function store(StoreRequest $request): HttpJsonResponse
    {
        $this->categoryRepository->storeRecord(
            $request->name
        );
        return JsonResponse::created();
    }

    public function update($id, UpdateRequest $request): HttpJsonResponse
    {
        $this->categoryRepository->updateRecord(
            $id,
            $request->name
        );
        return JsonResponse::updated();
    }
}
