<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::inRandomOrder()->first() ?? Order::factory(),
            'amount' => $this->faker->randomFloat(2, 100, 5000),
            'provider' => $this->faker->randomElement(['Visa', 'MasterCard', 'SBP', 'Tinkoff', 'Yandex']),
            'transaction_id' => $this->faker->unique()->numerify('TXN########'),
            'status' => $this->faker->randomElement(['Успешно', 'Отклонено', 'В обработке']),
        ];
    }
}
