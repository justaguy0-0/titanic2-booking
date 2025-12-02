<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class OrderFactory extends Factory
{
    protected $model = \App\Models\Order::class;

    public function definition(): array
    {
        $totalPrice = $this->faker->randomFloat(2, 200, 10000);
        $loyaltyDiscount = $this->faker->randomFloat(2, 0, 15);
        $finalPrice = $totalPrice - ($totalPrice * $loyaltyDiscount / 100);

        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory()->create()->id,
            'total_price' => $totalPrice,
            'status' => $this->faker->randomElement(['Новый', 'Обработан', 'Оплачен', 'Отправлен', 'Отменён']),
            'ticket_count' => $this->faker->numberBetween(1, 5),
            'loyalty_discount_applied' => $loyaltyDiscount,
            'final_price' => $finalPrice,
        ];
    }
}
