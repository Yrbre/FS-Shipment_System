<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'po',
        'no_invoice',
        'no_bl',
        'supplier_id',
        'department_id',
        'status_id',
        'created_by',
        'etd',
        'eta',
        'changed_by',
        'notes',
    ];

    protected $casts = [
        'etd' => 'date',
        'eta' => 'date',
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    public function items()
    {
        return $this->hasMany(ShipmentHistoryItems::class);
    }
}
