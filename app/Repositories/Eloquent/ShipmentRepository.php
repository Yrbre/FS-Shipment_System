<?php

namespace App\Repositories\Eloquent;

use App\Models\Shipment;
use App\Repositories\Interfaces\ShipmentRepositoryInterface;


class ShipmentRepository extends BaseRepository implements ShipmentRepositoryInterface
{

    public function __construct(Shipment $model)
    {
        return parent::__construct($model);
    }

    public function quary()
    {
        return $this->model->newQuery();
    }

    public function allWithRelations()
    {
        return $this->model->with(
            [
                'supplier',
                'department',
                'status',
                'creator',
                'item',
                'histories',
                'imcVerif',
            ]
        )->latest()->get();
    }

    public function getAll()
    {
        return $this->model->get();
    }

    public function findWithRelations(int $id)
    {
        return $this->model
            ->with([
                'supplier',
                'department',
                'status',
                'creator',
                'items.item',
                'imcVerif.warehouse',
                'histories' => fn($q) => $q->with([
                    'changedBy',
                    'status',
                    'supplier',
                    'items.item',
                ])->orderBy('created_at', 'asc'), // ← asc agar mudah dibandingkan
            ])
            ->findOrFail($id);
    }


    public function getByDepartment(int $departmentId)
    {
        return $this->model->with(
            [
                'supplier',
                'status',
            ]
        )->where('department_id', $departmentId)
            ->latest()
            ->get();
    }

    public function getPendingVerif()
    {
        return $this->model
            ->with(['supplier', 'department', 'status', 'items.item'])
            ->whereDoesntHave('imcVerification')
            ->latest()
            ->get();
    }

    public function updateStatus(int $id, int $statusId): bool
    {
        return $this->model->findOrFail($id)->update(['status_id' => $statusId]);
    }
}
