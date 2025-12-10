<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClearExpiredOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:clear-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Отменяет неоплаченные заказы старше 10 минут и освобождает забронированные билеты';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Начинается проверка истекших заказов...');


        $expiredOrders = Order::where('status', 'Новый')
            ->where('created_at', '<=', now()->subMinutes(10))
            ->with('orderItems.ticket')
            ->get();

        if ($expiredOrders->isEmpty()) {
            $this->info('Истекших заказов не найдено.');
            return 0;
        }

        $clearedCount = 0;
        $freedTicketsCount = 0;

        DB::beginTransaction();
        try {
            foreach ($expiredOrders as $order) {
                foreach ($order->orderItems as $orderItem) {
                    if ($orderItem->ticket && $orderItem->ticket->status === 'Забронировано') {
                        $orderItem->ticket->update(['status' => 'Доступно']);
                        $freedTicketsCount++;
                    }
                }

                $order->update(['status' => 'Отменён']);
                $clearedCount++;

                $this->line("Заказ #{$order->id} отменён, освобождено билетов: {$order->orderItems->count()}");
            }

            DB::commit();

            $this->info("✓ Успешно обработано заказов: {$clearedCount}");
            $this->info("✓ Освобождено билетов: {$freedTicketsCount}");

            Log::info("[ClearExpiredOrders] Обработано заказов: {$clearedCount}, освобождено билетов: {$freedTicketsCount}");

            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Ошибка при очистке заказов: ' . $e->getMessage());
            Log::error('[ClearExpiredOrders] Ошибка: ' . $e->getMessage());
            return 1;
        }
    }
}
