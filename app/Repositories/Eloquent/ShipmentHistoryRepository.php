<?php

namespace App\Repositories\Eloquent;

use App\Models\ShipmentHistory;
use App\Models\ShipmentHistoryItems;
use App\Repositories\Interfaces\ShipmentHistoryRepositoryInterface;

class ShipmentHistoryRepository extends BaseRepository implements ShipmentHistoryRepositoryInterface
{
    public function __construct(ShipmentHistory $model)
    {
        return parent::__construct($model);
    }

    public function createSnapshot(array $shipmentData, array $items, int $changedBy, string $notes = null)
    {
        // Simpan header history
        $history = $this->model->create([
            'shipment_id'   => $shipmentData['id'],
            'po'            => $shipmentData['po'],
            'no_invoice'    => $shipmentData['no_invoice'],
            'no_bl'         => $shipmentData['no_bl'],
            'supplier_id'   => $shipmentData['supplier_id'],
            'department_id' => $shipmentData['department_id'],
            'status_id'     => $shipmentData['status_id'],
            'etd'           => $shipmentData['etd'],
            'eta'           => $shipmentData['eta'],
            'created_by'    => $changedBy,
            'notes'         => $notes,
        ]);

        // Simpan snapshot items
        $historyItems = collect($items)->map(fn($item) => [
            'shipment_history_id' => $history->id,
            'item_id'             => data_get($item, 'item_id'),
            'rf'                  => data_get($item, 'rf'),
            'hscode'              => data_get($item, 'hscode'),
            'quantity'            => data_get($item, 'quantity'),
            'uom'                 => data_get($item, 'uom'),
            'notes'               => data_get($item, 'notes'),
            'created_at'          => now(),
            'updated_at'          => now(),
        ])->toArray();

        if (!empty($historyItems)) {
            ShipmentHistoryItems::insert($historyItems);
        }
    }

    public function getHistory(int $shipmentId, int $historyId)
    {
        return $this->model->with('items.item')
            ->where('shipment_id', $shipmentId)
            ->where('id', $historyId)
            ->firstOrFail();
    }
}
