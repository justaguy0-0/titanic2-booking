<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');

            $table->foreignId('ticket_id')
                ->nullable()
                ->constrained('tickets')
                ->onDelete('set null');

            $table->foreignId('entertainment_id')
                ->nullable()
                ->constrained('entertainments')
                ->onDelete('set null');

            $table->enum('type', ['ticket', 'entertainment']);

            $table->decimal('price', 10, 2);

            $table->unsignedInteger('quantity')->default(1);

            $table->timestamps();

            $table->engine = 'InnoDB';
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
