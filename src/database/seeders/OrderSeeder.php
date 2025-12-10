<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Order;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        if (User::count() === 0) {
            throw new \Exception('Пользователи отсутствуют.');
        }

        if (Order::count() < 20) {
            Order::factory()->count(20 - Order::count())->create();
        }
    }
}
