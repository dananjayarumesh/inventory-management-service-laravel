<?php

namespace App\Http\Controllers;

use App\Exceptions\AuthFailedException;
use App\Http\Requests\Auth\AuthenticateRequest;
use App\Http\Responses\JsonResponse;
use App\Services\AuthServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(AuthenticateRequest $request)
    {
        try {
            return response()->json([
                'access_token' => $this->authService->getAccessTokenByCred(
                    $request->email,
                    $request->password
                )
            ]);
        } catch (AuthFailedException $exception) {
            return JsonResponse::unauthenticated($exception->getMessage());
        }
    }
}
