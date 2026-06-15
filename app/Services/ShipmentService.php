<?php

namespace App\Services;

use App\Models\Item;
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
            $data['status_id']  = Status::where('name', 'Draft')->value('id');

            // 1. Simpan shipment
            $shipment = $this->shipmentRepository->create($data);

            $resolvedItems = [];

            foreach ($items as $item) {
                // 2. firstOrCreate item untuk dapat item_id
                $itemRecord = Item::firstOrCreate(
                    ['name' => $item['item_name']],
                    ['uom'  => $item['uom'] ?? null]
                );

                // 3. Simpan ke shipment_items
                $shipmentItem = $this->shipmentItemRepository->create([
                    'shipment_id' => $shipment->id,
                    'item_id'     => $itemRecord->id,
                    'rf'          => $item['rf'] ?? null,
                    'hscode'      => $item['hscode'] ?? null,
                    'quantity'    => $item['quantity'] ?? null,
                    'uom'         => $item['uom'] ?? null,
                    'notes'       => $item['notes'] ?? null,
                ]);

                $resolvedItems[] = array_merge($item, [
                    'item_id' => $itemRecord->id,
                ]);
            }

            // 4. Buat snapshot history
            $this->shipmentHistoryRepository->createSnapshot(
                $shipment->toArray(),
                $resolvedItems,
                auth()->id(),
                $data['notes'] ?? null
            );

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

            // Sync items & dapat resolvedItems dengan item_id
            $resolvedItems = $this->syncShipmentItems($id, $items);

            $updatedShipment = $this->shipmentRepository->find($id);

            $this->shipmentHistoryRepository->createSnapshot(
                $updatedShipment->toArray(),
                $resolvedItems,
                $changedBy,
                $data['notes'] ?? null
            );

            return true;
        });
    }

    private function syncShipmentItems(int $shipmentId, array $newItems): array
    {
        $existing = $this->shipmentItemRepository->getByShipment($shipmentId)->keyBy('rf');

        $incomingRfs = collect($newItems)->pluck('rf')->filter()->toArray();

        // Hapus item yang tidak ada di request
        $existing->whereNotIn('rf', $incomingRfs)
            ->each(fn($item) => $item->delete());

        $resolvedItems = [];

        foreach ($newItems as $item) {
            $uom = $item['uom'] ?? null;

            // firstOrCreate item untuk dapat item_id
            $itemRecord = Item::firstOrCreate(
                ['name' => $item['item_name']],
                ['uom'  => $uom]
            );

            $existingItem = $existing->get($item['rf'] ?? null);

            if ($existingItem) {
                // Update item yang sudah ada
                $existingItem->update([
                    'item_id'  => $itemRecord->id,
                    'rf'       => $item['rf'] ?? null,
                    'hscode'   => $item['hscode'] ?? null,
                    'quantity' => $item['quantity'] ?? null,
                    'uom'      => $uom,
                    'notes'    => $item['notes'] ?? null,
                ]);
            } else {
                // Insert item baru
                $this->shipmentItemRepository->create([
                    'shipment_id' => $shipmentId,
                    'item_id'     => $itemRecord->id,
                    'rf'          => $item['rf'] ?? null,
                    'hscode'      => $item['hscode'] ?? null,
                    'quantity'    => $item['quantity'] ?? null,
                    'uom'         => $uom,
                    'notes'       => $item['notes'] ?? null,
                ]);
            }

            $resolvedItems[] = array_merge($item, ['item_id' => $itemRecord->id]);
        }

        return $resolvedItems;
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

    public function getHistory(int $shipmentId, int $historyId)
    {
        return $this->shipmentHistoryRepository->getHistory($shipmentId, $historyId);
    }
}
