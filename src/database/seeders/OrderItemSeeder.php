<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\Entertainment;
use App\Models\OrderItem;
use Faker\Factory as Faker;

class OrderItemSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create(); // Инициализация Faker
        
        if (Order::count() === 0) {
            throw new \Exception('Нет заказов. Запустите OrderSeeder.');
        }
        
        $orders = Order::with('user')->get();
        $availableTickets = Ticket::where('status', 'Доступно')->pluck('id')->toArray();
        $entertainments = Entertainment::pluck('id')->toArray();
        
        if (empty($availableTickets) && empty($entertainments)) {
            throw new \Exception('Нет доступных билетов или развлечений.');
        }
        
        foreach ($orders as $order) {
            $numItems = $faker->numberBetween(1, 4);
            
            for ($i = 0; $i < $numItems; $i++) {
                $type = $faker->randomElement(
                    array_filter([
                        !empty($availableTickets) ? 'ticket' : null,
                        !empty($entertainments) ? 'entertainment' : null,
                    ])
                );
                
                if ($type === 'ticket') {
                    $ticketId = $faker->randomElement($availableTickets);
                    $ticket = Ticket::find($ticketId);
                    
                    OrderItem::create([
                        'order_id'         => $order->id,
                        'ticket_id'        => $ticketId,
                        'entertainment_id' => null,
                        'item_type'        => 'ticket', // Изменено с 'type' на 'item_type'
                        'price'            => $ticket->price,
                        'quantity'         => 1,
                    ]);
                    
                    $ticket->update(['status' => 'Забронировано']);
                    $availableTickets = array_diff($availableTickets, [$ticketId]);
                } else {
                    $entertainmentId = $faker->randomElement($entertainments);
                    $entertainment = Entertainment::find($entertainmentId);
                    $quantity = $faker->numberBetween(1, 3);
                    
                    OrderItem::create([
                        'order_id'         => $order->id,
                        'ticket_id'        => null,
                        'entertainment_id' => $entertainmentId,
                        'item_type'        => 'entertainment', // Изменено с 'type' на 'item_type'
                        'price'            => $entertainment->price,
                        'quantity'         => $quantity,
                    ]);
                }
            }
            
            // Обновляем total_price
            $order->refreshTotalPrice();
        }
    }
}

//$orders = Order::all();
//$availableTickets = Ticket::where('status', 'Доступно')->get();
//
//foreach ($orders as $order) {
//    // Берём 1-2 случайных доступных билета
//    $tickets = $availableTickets->random(min(2, $availableTickets->count()));
//
//    $totalPrice = 0;
//
//    foreach ($tickets as $ticket) {
//        OrderItem::create([
//            'order_id' => $order->id,
//            'ticket_id' => $ticket->id,
//            'entertainment_id' => null,
//            'item_type' => 'ticket',
//            'quantity' => 1,
//            'price' => $ticket->price,
//        ]);
//
//        $ticket->update(['status' => 'Забронирован']);
//        $totalPrice += $ticket->price;
//
//        // Удаляем из доступных
//        $availableTickets = $availableTickets->reject(fn($t) => $t->id === $ticket->id);
//    }
//
//    // Обновляем цену заказа
//    $order->update(['total_price' => $totalPrice]);
//
//    if ($availableTickets->isEmpty()) {
//        break;
//    }
