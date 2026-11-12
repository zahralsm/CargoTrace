<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackingEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'location_id',
        'event_type',
        'event_time',
        'location_name',
        'latitude',
        'longitude',
        'description',
        'created_by',
    ];

    protected $casts = [
        'event_time' => 'datetime',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

