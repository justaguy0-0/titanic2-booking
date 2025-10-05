<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\IcebergArrival;
use App\Models\PlaceDeparture;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Voyage>
 */
class VoyageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $departureDate = $this->faker->dateTimeBetween('+1 month', '+1 year');
        return [
            'name' => $this->faker->randomElement([
                'Великолепный трансатлантический круиз',
                'Titanic Legacy Voyage',
                'Атлантический экспресс',
                'Королевский путь',
                'Звёздный рейс'
            ]),
            'place_departure' => PlaceDeparture::factory(),
            'iceberg_arrival' => IcebergArrival::factory(), 
            'departure_date' => $departureDate,
            'arrival_date' => $this->faker->dateTimeBetween($departureDate, $departureDate->modify('+7 days')),
            'travel_time' => $this->faker->numberBetween(5, 14), // Дни в пути
            'base_price' => $this->faker->randomFloat(2, 500, 5000),
        ];
    }
}
