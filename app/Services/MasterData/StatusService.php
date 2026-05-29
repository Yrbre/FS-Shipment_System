<?php

namespace App\Services\MasterData;

use App\Repositories\Interfaces\StatusRepositoryInterface;

class StatusService
{
    public function __construct(
        private StatusRepositoryInterface $statusRepository
    ) {}

    public function getAll()
    {
        return $this->statusRepository->getAll();
    }

    public function getById(int $id)
    {
        return $this->statusRepository->getById($id);
    }

    public function create(array $data)
    {
        return $this->statusRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->statusRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->statusRepository->delete($id);
    }
}
