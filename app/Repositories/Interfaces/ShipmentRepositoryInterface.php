<?php

namespace App\Repositories\Interfaces;

interface ShipmentRepositoryInterface extends BaseRepositoryInterface
{
    public function quary();

    public function allWithRelations();

    public function findWithRelations(int $id);

    public function getByDepartment(int $departmentId);

    public function getPendingVerif();

    public function updateStatus(int $id, int $statusId);
}
