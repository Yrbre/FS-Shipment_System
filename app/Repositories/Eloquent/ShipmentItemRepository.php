<?php

namespace App\Repositories\Eloquent;

use App\Models\ShipmentItem;
use App\Repositories\Interfaces\ShipmentItemRepositoryInterface;
use Override;

class ShipmentItemRepository extends BaseRepository implements ShipmentItemRepositoryInterface
{

    public function __construct(ShipmentItem $model)
    {
        return parent::__construct($model);
    }


    public function getByShipment(int $shipmentId)
    {
        return $this->model->with('item')->where('shipment_id', $shipmentId)->get();
    }

    #[Override]
    public function deleteByShipment(int $shipmentId)
    {
        return $this->model->where('shipment_id', $shipmentId)->delete();
    }
}
