<?php

namespace App\Services;

use App\Repositories\Interfaces\ImcVerifRepositoryInterface;
use App\Repositories\Interfaces\ShipmentHistoryRepositoryInterface;
use App\Repositories\Interfaces\ShipmentRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ImcVerifService
{
    public function __construct(
        private ImcVerifRepositoryInterface $imcVerifRepository,
        private ShipmentRepositoryInterface $shipmentRepository,
        private ShipmentHistoryRepositoryInterface $shipmentHistoryRepository,
        private ShipmentService $shipmentService,
    ) {}

    public function getPendingShipments()
    {
        return $this->shipmentRepository->getPendingVerif();
    }

    public function verify(int $shipmentId, array $data, array $items)
    {
        return DB::transaction(function () use ($shipmentId, $data, $items) {
            $verifData = [
                'shipment_id'   => $shipmentId,
                'warehouse_id'  => $data['warehouse_id'],
                'verifed_by'    => auth()->id(),
                'verifed_at'    => now(),
                'status_id'        => $data['status_id'],
                'notes'         => $data['notes'] ?? null,
            ];

            $verification = $this->imcVerifRepository->createWithItems($verifData, $items);

            return $verification;
        });
    }

    public function findByShipment(int $shipmentId)
    {
        return $this->imcVerifRepository->findByShipmentId($shipmentId);
    }
}
