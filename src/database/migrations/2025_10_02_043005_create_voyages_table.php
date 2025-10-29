<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voyages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->foreignId('departure_place_id')->constrained('places')->onDelete('restrict');
            $table->foreignId('arrival_place_id')->constrained('places')->onDelete('restrict');
            $table->timestamp('departure_date');
            $table->timestamp('arrival_date');
            $table->integer('travel_time');
            $table->decimal('base_price', 10, 2);
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voyages');
    }
};
