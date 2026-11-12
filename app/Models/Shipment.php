<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'shipment_number',
        'customer_id',
        'carrier_id',
        'origin_id',
        'destination_id',
        'cargo_type',
        'weight_kg',
        'volume_cbm',
        'current_status',
        'expected_departure',
        'expected_arrival',
        'estimated_arrival',
        'actual_departure',
        'actual_arrival',
        'delay_status',
        'delay_hours',
        'delay_severity',
        'delay_reason',
        'notes',
    ];

    protected $casts = [
        'weight_kg' => 'float',
        'volume_cbm' => 'float',
        'expected_departure' => 'datetime',
        'expected_arrival' => 'datetime',
        'estimated_arrival' => 'datetime',
        'actual_departure' => 'datetime',
        'actual_arrival' => 'datetime',
        'delay_hours' => 'integer',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function carrier()
    {
        return $this->belongsTo(Carrier::class);
    }

    public function origin()
    {
        return $this->belongsTo(Location::class, 'origin_id');
    }

    public function destination()
    {
        return $this->belongsTo(Location::class, 'destination_id');
    }

    public function items()
    {
        return $this->hasMany(ShipmentItem::class);
    }

    public function trackingEvents()
    {
        return $this->hasMany(TrackingEvent::class)->orderBy('event_time', 'asc');
    }

    public function latestEvent()
    {
        return $this->hasOne(TrackingEvent::class)->latestOfMany('event_time');
    }

    public function exceptions()
    {
        return $this->hasMany(ShipmentException::class)->orderBy('detected_at', 'desc');
    }

    public function openExceptions()
    {
        return $this->hasMany(ShipmentException::class)->where('status', '!=', 'RESOLVED');
    }

    public function scopeForUser($query, User $user)
    {
        if ($user->isCustomer()) {
            return $query->where('customer_id', $user->id);
        }
        return $query;
    }
}

