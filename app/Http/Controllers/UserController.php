<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\StoreRequest;
use App\Http\Requests\Users\UpdateRequest;
use App\Http\Responses\JsonResponse;
use App\Repositories\UserRepositoryInterface;
use App\Services\UserServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse as HttpJsonResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $userRepository;
    protected $userService;

    public function __construct(UserRepositoryInterface $userRepository, UserServiceInterface $userService)
    {
        $this->userRepository = $userRepository;
        $this->userService = $userService;
    }

    public function index(): HttpJsonResponse
    {
        return JsonResponse::success(
            $this->userRepository->getRecords()
        );
    }

    public function store(StoreRequest $request): HttpJsonResponse
    {
        $this->userService->storeUser(
            $request->name,
            $request->email,
            $request->role,
            $request->password
        );
        return JsonResponse::created();
    }

    public function update(int $id, UpdateRequest $request): HttpJsonResponse
    {
        $this->userService->updateUser(
            $id,
            $request->name,
            $request->password
        );
        return JsonResponse::updated();
    }

    public function destroy(int $id): HttpJsonResponse
    {
        if (Auth::user()->id === $id) {
            return JsonResponse::forbidden('Cannot delete this user at the moment.');
        }
        $this->userRepository->deleteRecord($id);
        return JsonResponse::deleted();
    }
}
