<?php

namespace App\Services\MasterData;

use App\Repositories\Interfaces\WarehouseRepositoryInterface;

class WarehouseService
{
    public function __construct(
        private WarehouseRepositoryInterface $warehouseRepository
    ) {}

    public function getAll()
    {
        return $this->warehouseRepository->getAll();
    }

    public function getById(int $id)
    {
        return $this->warehouseRepository->getById($id);
    }

    public function create(array $data)
    {
        return $this->warehouseRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->warehouseRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->warehouseRepository->delete($id);
    }
}
