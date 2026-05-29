<?php

namespace App\Repositories\Eloquent;

use App\Models\ShipmentHistory;
use App\Repositories\Interfaces\ShipmentHistoryRepositoryInterface;

class ShipmentHistoryRepository extends BaseRepository implements ShipmentHistoryRepositoryInterface
{
    public function __construct(ShipmentHistory $model)
    {
        return parent::__construct($model);
    }

    public function createSnapshot(array $shipmentData, int $changedBy, string $notes = null)
    {
        $this->model->create([
            'shipment_id'       => $shipmentData['id'],
            'po'                => $shipmentData['po'],
            'no_invoice'        => $shipmentData['no_invoice'],
            'no_bl'             => $shipmentData['no_bl'],
            'supplier_id'       => $shipmentData['supplier_id'],
            'department_id'     => $shipmentData['department_id'],
            'status_id'         => $shipmentData['status_id'],
            'etd'               => $shipmentData['etd'],
            'eta'               => $shipmentData['eta'],
            'changed_by'        => $changedBy,
            'notes'             => $notes,
        ]);
    }
}
