<?php

namespace App\Services\MasterData;

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
}
