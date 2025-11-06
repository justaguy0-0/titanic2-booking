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

//if ($isTicket) {
//    // Элемент — билет
//    $ticket = Ticket::inRandomOrder()->first() ?? Ticket::factory()->create();
//
//    return [
//        'order_id' => Order::factory(), // создаст заказ
//        'ticket_id' => $ticket->id,
//        'entertainment_id' => null,
//        'item_type' => 'ticket',
//        'quantity' => 1,
//        'price' => $ticket->price,
//    ];
//} else {
//    // Элемент — развлечение
//    $entertainment = Entertainment::inRandomOrder()->first() ?? Entertainment::factory()->create();
//    $quantity = $this->faker->numberBetween(1, 5);
//
//    return [
//        'order_id' => Order::factory(),
//        'ticket_id' => null,
//        'entertainment_id' => $entertainment->id,
//        'item_type' => 'entertainment',
//        'quantity' => $quantity,
//        'price' => $entertainment->price * $quantity,
