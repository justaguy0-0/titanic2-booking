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

        
        $firstClass = CabinType::where('name', 'Первый класс')->first();
        $secondClass = CabinType::where('name', 'Второй класс')->first();
        $thirdClass = CabinType::where('name', 'Третий класс')->first();

        if (!$firstClass || !$secondClass || !$thirdClass) {
            throw new \Exception('Не найдены все типы кают');
        }

        
        $ticketsPerClass = [
            $firstClass->id => 12,   
            $secondClass->id => 18,  
            $thirdClass->id => 30,   
        ];

        
        $pricesPerClass = [
            $firstClass->id => [3000, 5000],   
            $secondClass->id => [1500, 2500],  
            $thirdClass->id => [500, 1000],    
        ];

        
        $trapezoidSeats = [
            $firstClass->id => [5, 11],    
            $secondClass->id => [8, 17],   
            $thirdClass->id => [14, 29],   
        ];

        
        $voyages = Voyage::all();
        $ticketCounter = 1;

        foreach ($voyages as $voyage) {
            foreach ($ticketsPerClass as $cabinTypeId => $count) {
                for ($i = 0; $i < $count; $i++) {
                    
                    $basePrice = rand($pricesPerClass[$cabinTypeId][0], $pricesPerClass[$cabinTypeId][1]);

                    
                    if (in_array($i, $trapezoidSeats[$cabinTypeId])) {
                        $basePrice = round($basePrice * 1.2);
                    }

                    Ticket::create([
                        'voyages_id' => $voyage->id,
                        'cabin_type_id' => $cabinTypeId,
                        'number' => 'TIT' . str_pad($ticketCounter, 4, '0', STR_PAD_LEFT),
                        'price' => $basePrice,
                        'status' => 'Доступно',
                    ]);
                    $ticketCounter++;
                }
            }
        }
    }
}
