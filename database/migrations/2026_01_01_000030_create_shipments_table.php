<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('shipment_number', 50)->unique()->index();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('carrier_id')->constrained('carriers')->onDelete('restrict');
            $table->foreignId('origin_id')->constrained('locations')->onDelete('restrict');
            $table->foreignId('destination_id')->constrained('locations')->onDelete('restrict');
            $table->string('cargo_type'); // e.g. Agricultural Produce, Dry Container, Electronics
            $table->decimal('weight_kg', 12, 2);
            $table->decimal('volume_cbm', 10, 2);
            
            // Lifecycle status: CREATED, BOOKED, PICKED_UP, IN_TRANSIT, DELIVERED
            $table->string('current_status', 30)->default('CREATED')->index();
            
            $table->dateTime('expected_departure');
            $table->dateTime('expected_arrival');
            $table->dateTime('estimated_arrival');
            $table->dateTime('actual_departure')->nullable();
            $table->dateTime('actual_arrival')->nullable();
            
            // Delay & SLA tracking
            $table->string('delay_status', 20)->default('ON_TIME')->index(); // ON_TIME, DELAYED
            $table->integer('delay_hours')->default(0);
            $table->string('delay_severity', 20)->default('NONE'); // NONE, LOW, MEDIUM, HIGH
            $table->string('delay_reason')->nullable();
            
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};

