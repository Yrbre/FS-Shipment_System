<?php

namespace App\Repositories\Eloquent;

use App\Models\ImcVerif;
use App\Models\ImcVerifItem;
use App\Repositories\Interfaces\ImcVerifRepositoryInterface;

class ImcVerifRepository extends BaseRepository implements ImcVerifRepositoryInterface
{

    public function __construct(ImcVerif $model)
    {
        return parent::__construct($model);
    }

    public function createWithItems(array $verifData, array $items)
    {
        $verif = $this->model->create($verifData);
        foreach ($items as $item) {
            ImcVerifItem::create(array_merge($item, ['verif_id' => $verif->id]));
        }
        return $verif;
    }


    public function findByShipmentId(int $shipmentId)
    {
        return $this->model
            ->with(['warehouse', 'verifedBy', 'items.shipmentItem.item', 'status'])
            ->where('shipment_id', $shipmentId)
            ->first();
    }
}
