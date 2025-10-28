<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\Ticket;

class OrderItemFactory extends Factory
{
    protected $model = \App\Models\OrderItem::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::inRandomOrder()->first()->id ?? Order::factory()->create()->id,
            'ticket_id' => Ticket::where('status', 'Доступно')->inRandomOrder()->first()->id ?? Ticket::factory()->create()->id,
        ];
    }
}
