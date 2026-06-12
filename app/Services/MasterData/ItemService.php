<?php

namespace App\Services\MasterData;

use App\Models\Item;
use App\Repositories\Interfaces\ItemRepositoryInterface;

class ItemService
{
    public function __construct(
        private ItemRepositoryInterface $itemRepository
    ) {}

    public function getAll()
    {
        return $this->itemRepository->getAll();
    }

    public function getById(int $id)
    {
        return $this->itemRepository->getById($id);
    }

    public function create(array $data)
    {
        return $this->itemRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->itemRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->itemRepository->delete($id);
    }

    public function firstOrCreateByName(string $name, ?string $uom = null)
    {
        return Item::firstOrCreate(
            ['name' => $name],
            [
                'code' => $this->generateItemCode($name),
                'uom'  => $uom,
            ]
        );
    }

    private function generateItemCode(string $name): string
    {
        $base   = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $name), 0, 6));
        $suffix = strtoupper(substr(md5($name . microtime(true) . uniqid()), 0, 4));

        $code = $base . '-' . $suffix;
        while (Item::where('code', $code)->exists()) {
            $suffix = strtoupper(substr(md5(uniqid()), 0, 4));
            $code   = $base . '-' . $suffix;
        }

        return $code;
    }
}
