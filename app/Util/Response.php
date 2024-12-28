<?php

namespace App\Util;

class Response
{
    public static function paginate(array $data, int $page, int $perPage): array   
    {
        return [
            'page' => $page,
            'per_page' => $perPage,
            'data' => $data
        ];
    }
}
