<?php

namespace App\Repositories\Interfaces;

interface ShipmentHistoryRepositoryInterface extends BaseRepositoryInterface
{
    public function createSnapshot(array $shipmentData,array $items, int $changedBy, ?string $notes = null);

    public function getHistory(int $shipmentId,int $historyId);

}
