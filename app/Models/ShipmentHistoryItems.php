<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentHistoryItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_history_id',
        'item_id',
        'quantity',
        'uom',
        'notes',
    ];

    public function history()
    {
        return $this->belongsTo(ShipmentHistory::class, 'shipment_history_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
