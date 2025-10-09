<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
      
       $this->call(RoleSeeder::class);

        $this->call([
            PlaceDepartureSeeder::class,
            IcebergArrivalSeeder::class,
            VoyageSeeder::class,
            EntertainmentSeeder::class,
            TicketSeeder::class,
            OrderSeeder::class,
            OrderItemSeeder::class,
            PaymentSeeder::class,
        ]);

        User::factory(10)->create()->each(function ($user) {
            $user->assignRole('user');
        });

        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);
        $admin->assignRole('admin');
    }
}
