<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse as HttpJsonResponse;

class JsonResponse
{
    public static function paginate(array $data, int $page, int $perPage): HttpJsonResponse
    {
        return response()->json([
            'page' => $page,
            'per_page' => $perPage,
            'data' => $data
        ]);
    }

    public static function success(array $data, string $key = 'data'): HttpJsonResponse
    {
        return response()->json([
            $key => $data
        ]);
    }

    public static function created(): HttpJsonResponse
    {
        return response()->json([
            'success' => true
        ], 201);
    }

    public static function updated(): HttpJsonResponse
    {
        return response()->json([], 204);
    }

    public static function deleted(): HttpJsonResponse
    {
        return response()->json([], 204);
    }

    public static function validation(array $errors): HttpJsonResponse
    {
        return response()->json([
            'errors' => $errors
        ], 422);
    }

    public static function forbidden(string $message): HttpJsonResponse
    {
        return response()->json([
            'error' => $message
        ], 403);
    }

    public static function unauthenticated(string $message): HttpJsonResponse
    {
        return response()->json([
            'error' => $message
        ], 401);
    }
}
