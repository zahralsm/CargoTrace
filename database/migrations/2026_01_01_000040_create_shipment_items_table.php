<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipment_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained('shipments')->onDelete('cascade');
            $table->string('item_name');
            $table->string('commodity_code', 30)->nullable(); // e.g. HS Code 0901.11 (Coffee)
            $table->integer('quantity')->default(1);
            $table->string('unit', 30)->default('CONTAINER_20FT'); // BOX, PALLET, BAG, CONTAINER_20FT, CONTAINER_40FT
            $table->decimal('weight_kg', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipment_items');
    }
};

