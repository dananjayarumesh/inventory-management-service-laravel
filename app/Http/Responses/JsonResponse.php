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

    public static function validationError(array $errors): HttpJsonResponse
    {
        return response()->json([
            'errors' => $errors
        ], 422);
    }
}
