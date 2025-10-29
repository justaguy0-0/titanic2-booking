<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class OrderFactory extends Factory
{
    protected $model = \App\Models\Order::class;

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory()->create()->id,
            'total_price' => $this->faker->randomFloat(2, 200, 10000),
            'status' => $this->faker->randomElement(['Новый', 'Обработан', 'Оплачен', 'Отправлен', 'Отменён']),
        ];
    }
}
