<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Place;

class PlaceSeeder extends Seeder
{
    public function run(): void
    {
        $places = [
            ['name' => 'Титаноград', 'type' => 'departure'],
            ['name' => 'Непотопинск', 'type' => 'departure'],
            ['name' => 'Селфи-Харбор', 'type' => 'departure'],
            ['name' => 'Северная Надежда', 'type' => 'departure'],
            ['name' => 'Суденск', 'type' => 'departure'],
            ['name' => 'Вайс-Сити', 'type' => 'departure'],
            ['name' => 'Айсберг №1912', 'type' => 'arrival'],
            ['name' => 'Айсберг имени Леонардо Ди Каприо', 'type' => 'arrival'],
            ['name' => 'Белый Убийца Атлантики', 'type' => 'arrival'],
            ['name' => 'Точка Невозврата', 'type' => 'arrival'],
            ['name' => 'Полярная Обнимашка', 'type' => 'arrival'],
            ['name' => 'Ледяная Глыба Североатлантики', 'type' => 'arrival'],
        ];

        foreach ($places as $place) {
            Place::firstOrCreate(
                ['name' => $place['name'], 'type' => $place['type']],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
