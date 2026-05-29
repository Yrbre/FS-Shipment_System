<?php

namespace App\Repositories\Interfaces;

interface ImcVerifRepositoryInterface extends BaseRepositoryInterface
{
    public function createWithItems(array $verifData, array $items);

    public function findByShipmentId(int $shipmentId);
}
