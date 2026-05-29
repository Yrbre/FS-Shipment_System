<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'po',
        'no_invoice',
        'no_bl',
        'supplier_id',
        'department_id',
        'status_id',
        'created_by',
        'etd',
        'eta',
        'notes'
    ];

    protected $casts = [
        'etd' => 'date',
        'eta' => 'date',
    ];

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

    public function item()
    {
        return $this->hasMany(ShipmentItem::class);
    }

    public function histories()
    {
        return $this->hasMany(ShipmentHistory::class);
    }

    public function imcVerif()
    {
        return $this->hasOne(ImcVerif::class);
    }
}
