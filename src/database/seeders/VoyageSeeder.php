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
        $departures = Place::where('type', 'departure')->pluck('id', 'name')->toArray();
        $arrivals = Place::where('type', 'arrival')->pluck('id', 'name')->toArray();

        if (empty($departures) || empty($arrivals)) {
            throw new \Exception('Места отправления или прибытия не найдены.');
        }

        $voyages = [
            [
                'name' => 'Путешествие к Айсбергу №1912',
                'departure_place_id' => $departures['Титаноград'] ?? reset($departures),
                'arrival_place_id' => $arrivals['Айсберг №1912'] ?? reset($arrivals),
                'departure_date' => Carbon::now()->addDays(10),
                'arrival_date' => Carbon::now()->addDays(15),
                'travel_time' => 120,
                'base_price' => 5000.00,
            ],
            [
                'name' => 'Экспедиция к Полярной Обнимашке',
                'departure_place_id' => $departures['Непотопинск'] ?? reset($departures),
                'arrival_place_id' => $arrivals['Полярная Обнимашка'] ?? reset($arrivals),
                'departure_date' => Carbon::now()->addDays(20),
                'arrival_date' => Carbon::now()->addDays(28),
                'travel_time' => 192,
                'base_price' => 7500.00,
            ],
            [
                'name' => 'Круиз к Ледяной Глыбе',
                'departure_place_id' => $departures['Селфи-Харбор'] ?? reset($departures),
                'arrival_place_id' => $arrivals['Ледяная Глыба Североатлантики'] ?? reset($arrivals),
                'departure_date' => Carbon::now()->addDays(30),
                'arrival_date' => Carbon::now()->addDays(36),
                'travel_time' => 144,
                'base_price' => 6000.00,
            ],
        ];

        foreach ($voyages as $voyage) {
            Voyage::create($voyage);
        }

        if (Voyage::count() < 10) {
            Voyage::factory()->count(10 - Voyage::count())->create();
        }
    }
}
