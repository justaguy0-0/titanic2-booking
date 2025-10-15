<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('voyages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->foreignId('place_departure')->constrained('place_departures')->onDelete('cascade');
            $table->foreignId('iceberg_arrival')->constrained('iceberg_arrivals')->onDelete('cascade');
            $table->timestamp('departure_date');
            $table->timestamp('arrival_date');
            $table->integer('travel_time');
            $table->decimal('base_price', 10, 2);
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voyages');
    }
};
