<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Entertainment>
 */
class EntertainmentFactory extends Factory
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
                'Вечерний ужин на палубе',
                'Спа-процедуры',
                'Концерт оркестра',
                'Танцевальный вечер',
                'Квест по кораблю'
            ]),
            'price' => $this->faker->randomFloat(2, 10, 200),
        ];
    }
}
