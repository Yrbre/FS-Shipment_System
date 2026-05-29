<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImcVerifItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'imc_verif_id',
        'shipment_item_id',
        'quantity_actial',
        'condition',
        'remarks',
    ];

    public function imcVerif()
    {
        return $this->belongsTo(ImcVerif::class);
    }

    public function shipmentItem()
    {
        return $this->belongsTo(ShipmentItem::class);
    }
}
