<?php

namespace App\Services;

use App\Repositories\Interfaces\ShipmentHistoryRepositoryInterface;
use App\Repositories\Interfaces\ShipmentItemRepositoryInterface;
use App\Repositories\Interfaces\ShipmentRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ShipmentService
{
    public function __construct(
        private ShipmentRepositoryInterface $shipmentRepository,
        private ShipmentItemRepositoryInterface $shipmentItemRepository,
        private ShipmentHistoryRepositoryInterface $shipmentHistoryRepository,
    ) {}

    public function getAll()
    {
        return $this->shipmentRepository->allWithRelations();
    }

    public function getByDepartment(int $departmentId)
    {
        return $this->shipmentRepository->getByDepartment($departmentId);
    }

    public function getPendingVerif()
    {
        return $this->shipmentRepository->getPendingVerif();
    }

    public function create(array $data, array $items)
    {
        return DB::transaction(function () use ($data, $items) {
            $data['created_by'] = auth()->id();
            $shipment = $this->shipmentRepository->create($data);

            foreach ($items as $item) {
                $this->shipmentItemRepository->create(array_merge($item, ['shipment_id' => $shipment->id]));
            }

            $this->shipmentHistoryRepository->createSnapshot($shipment->toArray(), Auth()->id(), $data['notes']);

            return $shipment;
        });
    }

    public function update(int $id, array $data, array $items)
    {
        return DB::transaction(function () use ($id, $data, $items) {
            $this->shipmentRepository->update($id, $data);
            $this->shipmentItemRepository->deleteByShipment($id);

            foreach ($items as $item) {
                $this->shipmentItemRepository->create(array_merge($item, ['shipment_id' => $id]));
            }

            $shipment = $this->shipmentRepository->find($id);
            $this->shipmentHistoryRepository->createSnapshot($shipment->toArray(), Auth()->id(), $data['notes']);

            return true;
        });
    }

    public function updateStatus(int $id, string $statusID, string $notes = null)
    {
        return DB::transaction(function () use ($id, $statusID, $notes) {
            $this->shipmentRepository->updateStatus($id, $statusID);
            $shipment = $this->shipmentRepository->find($id);
            $this->shipmentHistoryRepository->createSnapshot($shipment->toArray(), Auth()->id(), $notes);

            return true;
        });
    }

    public function delete(int $id)
    {
        return $this->shipmentRepository->delete($id);
    }
}
