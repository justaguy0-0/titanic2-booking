<?php

namespace Database\Seeders;

use App\Models\IcebergArrival;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IcebergArrivalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (IcebergArrival::count() < 5) {
            IcebergArrival::factory()->count(5)->create();
        }
    }
}
