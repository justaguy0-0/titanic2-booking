<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\Voyage;
use App\Models\CabinType;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        if (Voyage::count() === 0 || CabinType::count() === 0) {
            throw new \Exception('Рейсы или типы кают отсутствуют');
        }

        // Получаем все типы кают
        $firstClass = CabinType::where('name', 'Первый класс')->first();
        $secondClass = CabinType::where('name', 'Второй класс')->first();
        $thirdClass = CabinType::where('name', 'Третий класс')->first();

        if (!$firstClass || !$secondClass || !$thirdClass) {
            throw new \Exception('Не найдены все типы кают');
        }

        // Количество билетов по классам
        $ticketsPerClass = [
            $firstClass->id => 12,   // Первый класс: 12 билетов
            $secondClass->id => 18,  // Второй класс: 18 билетов
            $thirdClass->id => 30,   // Третий класс: 30 билетов
        ];

        // Цены по классам
        $pricesPerClass = [
            $firstClass->id => [3000, 5000],   // Первый класс: 3000-5000
            $secondClass->id => [1500, 2500],  // Второй класс: 1500-2500
            $thirdClass->id => [500, 1000],    // Третий класс: 500-1000
        ];

        // Для каждого рейса создаем билеты
        $voyages = Voyage::all();
        $ticketCounter = 1;

        foreach ($voyages as $voyage) {
            foreach ($ticketsPerClass as $cabinTypeId => $count) {
                for ($i = 0; $i < $count; $i++) {
                    Ticket::create([
                        'voyages_id' => $voyage->id,
                        'cabin_type_id' => $cabinTypeId,
                        'number' => 'TIT' . str_pad($ticketCounter, 4, '0', STR_PAD_LEFT),
                        'price' => rand($pricesPerClass[$cabinTypeId][0], $pricesPerClass[$cabinTypeId][1]),
                        'status' => 'Доступно',
                    ]);
                    $ticketCounter++;
                }
            }
        }
    }
}
