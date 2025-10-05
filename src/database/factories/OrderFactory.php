<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(), 
            'total_price' => $this->faker->randomFloat(2, 200, 10000), 
            'status' => $this->faker->randomElement(['Новый', 'Обработан', 'Оплачен', 'Отправлен', 'Отменён']),
        ];
    }
}
