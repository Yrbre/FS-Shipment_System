<?php

namespace App\Repositories\Interfaces;

interface ShipmentHistoryRepositoryInterface extends BaseRepositoryInterface
{
    public function createSnapshot(array $shipmentData, int $changedBy, string $notes = null);
}
