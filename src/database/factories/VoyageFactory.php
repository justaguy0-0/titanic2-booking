<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Place;
use Carbon\Carbon;

class VoyageFactory extends Factory
{
    public function definition(): array
    {
        $departureDate = $this->faker->dateTimeBetween('+1 month', '+1 year');
        $arrivalDate = $this->faker->dateTimeBetween(
            Carbon::instance($departureDate)->addDays(1),
            Carbon::instance($departureDate)->addDays(7)
        );
        $travelTime = round(Carbon::instance($departureDate)->diffInHours($arrivalDate));

        return [
            'name' => $this->faker->randomElement([
                'Великолепный трансатлантический круиз',
                'Titanic Legacy Voyage',
                'Атлантический экспресс',
                'Королевский путь',
                'Звёздный рейс'
            ]),
            'departure_place_id' => Place::where('type', 'departure')->inRandomOrder()->first()->id ?? Place::factory()->create(['type' => 'departure'])->id,
            'arrival_place_id' => Place::where('type', 'arrival')->inRandomOrder()->first()->id ?? Place::factory()->create(['type' => 'arrival'])->id,
            'departure_date' => $departureDate,
            'arrival_date' => $arrivalDate,
            'travel_time' => $travelTime,
            'base_price' => $this->faker->randomFloat(2, 500, 5000),
        ];
    }
}
