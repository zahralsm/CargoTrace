<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipment_exceptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained('shipments')->onDelete('cascade');
            $table->foreignId('tracking_event_id')->nullable()->constrained('tracking_events')->nullOnDelete();
            
            // Types per Slide 08
            $table->string('exception_type', 50); // CUSTOMS_HOLD, WEATHER_DELAY, PORT_CONGESTION, DAMAGED, MISROUTED, DOCUMENTATION_ISSUE
            $table->string('severity', 20)->default('LOW'); // LOW, MEDIUM, HIGH
            $table->string('status', 20)->default('OPEN'); // OPEN, INVESTIGATING, RESOLVED
            
            $table->text('description');
            $table->dateTime('detected_at');
            $table->dateTime('resolved_at')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('resolution_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipment_exceptions');
    }
};

