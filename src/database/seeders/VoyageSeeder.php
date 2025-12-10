<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Place;
use App\Models\Voyage;
use Carbon\Carbon;

class VoyageSeeder extends Seeder
{
    public function run(): void
    {
        
        Voyage::query()->delete();

        $departures = Place::where('type', 'departure')->pluck('id', 'name')->toArray();
        $arrivals = Place::where('type', 'arrival')->pluck('id', 'name')->toArray();

        if (empty($departures) || empty($arrivals)) {
            throw new \Exception('Места отправления или прибытия не найдены.');
        }

       
        $voyages = [
           
            [
                'name' => 'Путешествие к Айсбергу №1912',
                'departure_place_id' => $departures['Титаноград'],
                'arrival_place_id' => $arrivals['Айсберг №1912'],
                'departure_date' => Carbon::create(2025, 12, 16, 16, 30, 0),
                'arrival_date' => Carbon::create(2025, 12, 21, 16, 30, 0),
                'travel_time' => 120,
                'base_price' => 5000.00,
            ],
            
            [
                'name' => 'Экспедиция к Полярной Обнимашке',
                'departure_place_id' => $departures['Непотопинск'],
                'arrival_place_id' => $arrivals['Полярная Обнимашка'],
                'departure_date' => Carbon::create(2025, 12, 26, 16, 30, 0),
                'arrival_date' => Carbon::create(2026, 1, 3, 16, 30, 0),
                'travel_time' => 192,
                'base_price' => 7500.00,
            ],
          
            [
                'name' => 'Круиз к Ледяной Глыбе',
                'departure_place_id' => $departures['Селфи-Харбор'],
                'arrival_place_id' => $arrivals['Ледяная Глыба Североатлантики'],
                'departure_date' => Carbon::create(2026, 1, 5, 16, 30, 0),
                'arrival_date' => Carbon::create(2026, 1, 11, 16, 30, 0),
                'travel_time' => 144,
                'base_price' => 6000.00,
            ],
          
            [
                'name' => 'Королевский путь',
                'departure_place_id' => $departures['Вайс-Сити'],
                'arrival_place_id' => $arrivals['Полярная Обнимашка'],
                'departure_date' => Carbon::create(2026, 3, 10, 20, 32, 0),
                'arrival_date' => Carbon::create(2026, 3, 13, 3, 9, 0),
                'travel_time' => 55,
                'base_price' => 2016.00,
            ],
         
            [
                'name' => 'Великолепный трансатлантический круиз',
                'departure_place_id' => $departures['Селфи-Харбор'],
                'arrival_place_id' => $arrivals['Айсберг имени Леонардо Ди Каприо'],
                'departure_date' => Carbon::create(2026, 4, 12, 2, 24, 0),
                'arrival_date' => Carbon::create(2026, 4, 17, 15, 28, 0),
                'travel_time' => 133,
                'base_price' => 4592.00,
            ],
        
            [
                'name' => 'Titanic Legacy Voyage',
                'departure_place_id' => $departures['Вайс-Сити'],
                'arrival_place_id' => $arrivals['Айсберг имени Леонардо Ди Каприо'],
                'departure_date' => Carbon::create(2026, 6, 7, 9, 47, 0),
                'arrival_date' => Carbon::create(2026, 6, 11, 5, 2, 0),
                'travel_time' => 91,
                'base_price' => 4067.00,
            ],
         
            [
                'name' => 'Полярный экспресс',
                'departure_place_id' => $departures['Титаноград'],
                'arrival_place_id' => $arrivals['Полярная Обнимашка'],
                'departure_date' => Carbon::create(2026, 8, 2, 10, 19, 0),
                'arrival_date' => Carbon::create(2026, 8, 4, 19, 43, 0),
                'travel_time' => 57,
                'base_price' => 1503.00,
            ],
          
            [
                'name' => 'Королевский путь',
                'departure_place_id' => $departures['Селфи-Харбор'],
                'arrival_place_id' => $arrivals['Айсберг №1912'],
                'departure_date' => Carbon::create(2026, 8, 14, 21, 7, 0),
                'arrival_date' => Carbon::create(2026, 8, 18, 14, 36, 0),
                'travel_time' => 89,
                'base_price' => 3842.00,
            ],
         
            [
                'name' => 'Атлантический экспресс',
                'departure_place_id' => $departures['Селфи-Харбор'],
                'arrival_place_id' => $arrivals['Белый Убийца Атлантики'],
                'departure_date' => Carbon::create(2026, 11, 11, 10, 31, 0),
                'arrival_date' => Carbon::create(2026, 11, 15, 7, 21, 0),
                'travel_time' => 93,
                'base_price' => 3451.00,
            ],
          
            [
                'name' => 'Экстрим-тур',
                'departure_place_id' => $departures['Непотопинск'],
                'arrival_place_id' => $arrivals['Точка Невозврата'],
                'departure_date' => Carbon::create(2026, 12, 1, 2, 0, 0),
                'arrival_date' => Carbon::create(2026, 12, 4, 8, 30, 0),
                'travel_time' => 79,
                'base_price' => 4275.00,
            ],
        ];

        foreach ($voyages as $voyage) {
            Voyage::create($voyage);
        }
    }
}