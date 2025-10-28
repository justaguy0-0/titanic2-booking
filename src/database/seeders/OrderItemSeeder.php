<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\OrderItem;
use Faker\Factory as FakerFactory;

class OrderItemSeeder extends Seeder
{
    protected $faker;

    public function __construct()
    {
        $this->faker = FakerFactory::create('ru_RU');
    }

    public function run(): void
    {
        if (Order::count() === 0 || Ticket::count() === 0) {
            throw new \Exception('Заказы или билеты отсутствуют. Сначала запустите OrderSeeder и TicketSeeder.');
        }

        $orders = Order::pluck('id')->toArray();
        $tickets = Ticket::where('status', 'Доступно')->pluck('id')->toArray();

        if (empty($tickets)) {
            throw new \Exception('Нет доступных билетов для создания элементов заказа.');
        }

        foreach ($orders as $orderId) {
            // Ограничиваем количество элементов количеством доступных билетов
            $numItems = $this->faker->numberBetween(1, min(3, count($tickets)));
            $selectedTickets = $this->faker->randomElements($tickets, $numItems);

            foreach ($selectedTickets as $ticketId) {
                OrderItem::create([
                    'order_id' => $orderId,
                    'ticket_id' => $ticketId,
                ]);

                // Обновляем статус билета
                Ticket::where('id', $ticketId)->update(['status' => 'Забронировано']);
                // Удаляем использованный билет из списка доступных
                $tickets = array_diff($tickets, [$ticketId]);
            }

            // Обновляем total_price заказа
            $totalPrice = OrderItem::where('order_id', $orderId)
                ->join('tickets', 'order_items.ticket_id', '=', 'tickets.id')
                ->sum('tickets.price');
            Order::where('id', $orderId)->update(['total_price' => $totalPrice]);
        }
    }
}
