<?php

namespace App\Repositories\Interfaces;

interface ShipmentItemRepositoryInterface extends BaseRepositoryInterface
{
    public function getByShipment(int $shipmentId);

    public function deleteByShipment(int $shipmentId);
}
