<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\IcebergArrival>
 */
class IcebergArrivalFactory extends Factory
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
                'Нью-Йорк (США)',
                'Филадельфия (США)',
                'Балтимор (США)',
                'Галифакс (Канада)',
                'Лондон (Великобритания)'
            ]),
        ];
    }
}
