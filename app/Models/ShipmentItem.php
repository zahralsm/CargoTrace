<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'item_name',
        'commodity_code',
        'quantity',
        'unit',
        'weight_kg',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'weight_kg' => 'float',
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }
}

