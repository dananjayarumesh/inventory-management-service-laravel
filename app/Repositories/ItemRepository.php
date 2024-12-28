<?php

namespace App\Repositories;

use App\Models\Item;
use App\Repositories\ItemRepositoryInterface;

class ItemRepository implements ItemRepositoryInterface
{
    public function getPaginatedRecords(int $page, int $perPage): array
    {
        $skipCount = $page * $perPage - $perPage;
        return Item::skip($skipCount)
            ->take($perPage)
            ->get()
            ->toArray();
    }

    public function getSingleRecord(int $id): array
    {
        return Item::findOrFail($id)->toArray();
    }
}
