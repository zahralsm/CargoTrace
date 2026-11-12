<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentException extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'tracking_event_id',
        'exception_type',
        'severity',
        'status',
        'description',
        'detected_at',
        'resolved_at',
        'resolved_by',
        'resolution_notes',
    ];

    protected $casts = [
        'detected_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function trackingEvent()
    {
        return $this->belongsTo(TrackingEvent::class);
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}

