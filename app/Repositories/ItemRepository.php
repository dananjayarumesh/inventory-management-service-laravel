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

    public function storeRecord(string $name, int $categoryId, int $createdBy): void
    {
        Item::create([
            'name' => $name,
            'category_id' => $categoryId,
            'created_by' => $createdBy
        ]);
    }

    public function updateRecord(int $id, string $name, int $categoryId): void
    {
        Item::findOrFail($id)->update([
            'name' => $name,
            'category_id' => $categoryId
        ]);
    }
}
