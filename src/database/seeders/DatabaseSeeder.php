<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Сначала создаём роли
        $this->call(RoleSeeder::class);

        // Затем создаём пользователей
        User::factory(10)->create()->each(function ($user) {
            $user->assignRole('user');
        });

        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);
        $admin->assignRole('admin');

        // Затем остальные сиды
        $this->call([
            PlaceSeeder::class,
            CabinTypeSeeder::class,
            VoyageSeeder::class,
            EntertainmentSeeder::class,
            TicketSeeder::class,
            OrderSeeder::class,
            OrderItemSeeder::class,
            PaymentSeeder::class,
        ]);
    }
}
