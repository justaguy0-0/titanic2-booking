<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Обновляем все статусы 'Забронирован' на 'Забронировано'
        DB::table('tickets')
            ->where('status', 'Забронирован')
            ->update(['status' => 'Забронировано']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Откатываем изменения (на случай rollback)
        DB::table('tickets')
            ->where('status', 'Забронировано')
            ->update(['status' => 'Забронирован']);
    }
};
