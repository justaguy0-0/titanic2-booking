<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\OrderItem;
use App\Models\Entertainment;

class OrderItemSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::all();
        $availableTickets = Ticket::where('status', 'Доступно')->get();

        foreach ($orders as $order) {
            // Берём 1-2 случайных доступных билета
            $tickets = $availableTickets->random(min(2, $availableTickets->count()));
            
            $totalPrice = 0;

            foreach ($tickets as $ticket) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'ticket_id' => $ticket->id,
                    'entertainment_id' => null,
                    'item_type' => 'ticket',
                    'quantity' => 1,
                    'price' => $ticket->price,
                ]);

                $ticket->update(['status' => 'Забронирован']);
                $totalPrice += $ticket->price;

                // Удаляем из доступных
                $availableTickets = $availableTickets->reject(fn($t) => $t->id === $ticket->id);
            }

            // Обновляем цену заказа
            $order->update(['total_price' => $totalPrice]);

            if ($availableTickets->isEmpty()) {
                break;
            }
        }
    }
}