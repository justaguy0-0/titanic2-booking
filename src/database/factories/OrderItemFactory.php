<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\Entertainment;

class OrderItemFactory extends Factory
{
    protected $model = \App\Models\OrderItem::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(['ticket', 'entertainment']);

        if ($type === 'ticket') {
            $ticket = Ticket::where('status', 'Доступно')->inRandomOrder()->first()
                ?? Ticket::factory()->create(['status' => 'Доступно']);
            return [
                'order_id'         => Order::factory(),
                'ticket_id'        => $ticket->id,
                'entertainment_id' => null,
                'type'             => 'ticket',
                'price'            => $ticket->price,
                'quantity'         => 1,
            ];
        } else {
            $entertainment = Entertainment::inRandomOrder()->first()
                ?? Entertainment::factory()->create();
            return [
                'order_id'         => Order::factory(),
                'ticket_id'        => null,
                'entertainment_id' => $entertainment->id,
                'type'             => 'entertainment',
                'price'            => $entertainment->price,
                'quantity'         => $this->faker->numberBetween(1, 3),
            ];
        }
    }
}
