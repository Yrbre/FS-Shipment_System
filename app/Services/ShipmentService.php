<?php

namespace App\Services;

use App\Models\Status;
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

    public function getQuary()
    {
        return $this->shipmentRepository->quary();
    }

    public function getAll()
    {
        return $this->shipmentRepository->getAll();
    }

    public function getByDepartment(int $departmentId)
    {
        return $this->shipmentRepository->getByDepartment($departmentId);
    }

    public function findWithRelations(int $id)
    {
        return $this->shipmentRepository->findWithRelations($id);
    }

    public function getPendingVerif()
    {
        return $this->shipmentRepository->getPendingVerif();
    }

    public function create(array $data, array $items)
    {
        return DB::transaction(function () use ($data, $items) {
            $data['created_by'] = auth()->id();
            $data['status_id']  = Status::where('name', 'Pending')->value('id');
            $shipment = $this->shipmentRepository->create($data);

            foreach ($items as $item) {
                $this->shipmentItemRepository->create(array_merge($item, ['shipment_id' => $shipment->id]));
            }

            $this->shipmentHistoryRepository->createSnapshot($shipment->toArray(), $items, Auth()->id(), $data['notes']);

            return $shipment;
        });
    }

    public function update(int $id, array $data, array $items, int $changedBy)
    {
        return DB::transaction(function () use ($id, $data, $items, $changedBy) {
            $shipment = $this->shipmentRepository->find($id);

            if (!isset($data['status_id'])) {
                $data['status_id'] = $shipment->status_id;
            }

            $this->shipmentRepository->update($id, $data);

            // ── Sync items (bukan delete+insert) ──────────────────
            $this->syncShipmentItems($id, $items);

            $updatedShipment = $this->shipmentRepository->find($id);

            // Ambil items untuk disimpan di snapshot
            $currentItems = $this->shipmentItemRepository->getByShipment($id);

            $this->shipmentHistoryRepository->createSnapshot(
                $updatedShipment->toArray(),
                $currentItems->toArray(),   // ← snapshot item
                $changedBy,
                $data['notes'] ?? null
            );

            return true;
        });
    }

    private function syncShipmentItems(int $shipmentId, array $newItems): void
    {
        $existing = $this->shipmentItemRepository->getByShipment($shipmentId)
            ->keyBy('item_id');

        $incomingItemIds = collect($newItems)->pluck('item_id')->toArray();

        // Hapus item yang tidak ada di request (baru benar-benar dihapus)
        $existing->whereNotIn('item_id', $incomingItemIds)
            ->each(fn($item) => $item->delete()); // softdelete hanya yang removed

        foreach ($newItems as $item) {
            $existingItem = $existing->get($item['item_id']);

            if ($existingItem) {
                // Update jika ada perubahan
                $existingItem->update([
                    'quantity' => $item['quantity'],
                    'uom'      => $item['uom'],
                    'notes'    => $item['notes'],
                ]);
            } else {
                // Insert baru
                $this->shipmentItemRepository->create(
                    array_merge($item, ['shipment_id' => $shipmentId])
                );
            }
        }
    }

    public function updateStatus(int $id, string $statusID, ?string $notes = null)
    {
        return DB::transaction(function () use ($id, $statusID, $notes) {
            $shipment     = $this->shipmentRepository->find($id);
            $currentItems = $this->shipmentItemRepository->getByShipment($id);

            $this->shipmentHistoryRepository->createSnapshot(
                $shipment->toArray(),
                $currentItems->toArray(),
                auth()->id(),
                $notes
            );

            return true;
        });
    }

    public function delete(int $id)
    {
        return $this->shipmentRepository->delete($id);
    }

    public function getHistory(int $shipmentId,int $historyId)
    {
        return $this->shipmentHistoryRepository->getHistory($shipmentId, $historyId);
    }

}
