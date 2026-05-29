<?php

namespace App\Services\MasterData;

use App\Repositories\Interfaces\SupplierRepositoryInterface;

class SupplierService
{
    public function __construct(
        private SupplierRepositoryInterface $supplierRepository
    ) {}

    public function getAll()
    {
        return $this->supplierRepository->getAll();
    }

    public function getById(int $id)
    {
        return $this->supplierRepository->getById($id);
    }

    public function create(array $data)
    {
        return $this->supplierRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->supplierRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->supplierRepository->delete($id);
    }
}
