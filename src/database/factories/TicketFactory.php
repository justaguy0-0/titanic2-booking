<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Model\Ticket;
use App\Models\Voyage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'voyages_id' => Voyage::factory(),
            'type' => $this->faker->randomElement(['Первый класс', 'Второй класс', 'Третий класс', 'Люкс']),
            'number' => $this->faker->unique()->numerify('TIT####'),
            'price' => $this->faker->randomFloat(2, 100, 2000),
            'status' => $this->faker->randomElement(['Доступно', 'Забронировано', 'Продано', 'Отменено']),
        ];
    }
}
