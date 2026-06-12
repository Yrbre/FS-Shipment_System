<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShipmentItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'shipment_id',
        'item_id',
        'quantity',
        'uom',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function verifItem()
    {
        return $this->hasOne(ImcVerifItem::class);
    }
}
