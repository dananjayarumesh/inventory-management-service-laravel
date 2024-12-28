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

    public static function view(array $data, string $key = 'data'): HttpJsonResponse
    {
        return response()->json([
            $key => $data
        ]);
    }
}
