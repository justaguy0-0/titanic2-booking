<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PlaceDeparture>
 */
class PlaceDepartureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement([
                'Саутгемптон (Великобритания)',
                'Ливерпуль (Великобритания)',
                'Нью-Йорк (США)',
                'Бостон (США)',
                'Гавр (Франция)'
            ]),
        ];
    }
}
