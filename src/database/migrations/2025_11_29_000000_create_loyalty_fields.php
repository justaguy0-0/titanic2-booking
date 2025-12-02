<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('total_tickets')->default(0);
            $table->integer('loyalty_level')->default(1);
            $table->decimal('loyalty_discount', 5, 2)->default(0);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->integer('ticket_count')->default(1);
            $table->decimal('loyalty_discount_applied', 5, 2)->default(0);
            $table->decimal('final_price', 10, 2);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['total_tickets', 'loyalty_level', 'loyalty_discount']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['ticket_count', 'loyalty_discount_applied', 'final_price']);
        });
    }
};
