<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\Entertainment;
use App\Models\OrderItem;
use App\Models\Ticket;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
   public function definition(): array
    {
        // 70% — билеты, 30% — развлечения
        $isTicket = $this->faker->boolean(70);

        if ($isTicket) {
            // Элемент — билет
            $ticket = Ticket::inRandomOrder()->first() ?? Ticket::factory()->create();

            return [
                'order_id' => Order::factory(), // создаст заказ
                'ticket_id' => $ticket->id,
                'entertainment_id' => null,
                'item_type' => 'ticket',
                'quantity' => 1,
                'price' => $ticket->price,
            ];
        } else {
            // Элемент — развлечение
            $entertainment = Entertainment::inRandomOrder()->first() ?? Entertainment::factory()->create();
            $quantity = $this->faker->numberBetween(1, 5);

            return [
                'order_id' => Order::factory(),
                'ticket_id' => null,
                'entertainment_id' => $entertainment->id,
                'item_type' => 'entertainment',
                'quantity' => $quantity,
                'price' => $entertainment->price * $quantity,
            ];
        }
    }
}
