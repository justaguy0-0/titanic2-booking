<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CabinType;

class CabinTypeSeeder extends Seeder
{
    public function run(): void
    {
        $cabinTypes = [
            ['name' => 'Первый класс', 'description' => 'Комфортабельная каюта с видом на море.'],
            ['name' => 'Второй класс', 'description' => 'Уютная каюта с базовыми удобствами.'],
            ['name' => 'Третий класс', 'description' => 'Экономичный вариант для путешественников.'],
            ['name' => 'Люкс', 'description' => 'Роскошная каюта с премиум-услугами.'],
        ];

        foreach ($cabinTypes as $cabinType) {
            CabinType::create($cabinType);
        }
    }
}
