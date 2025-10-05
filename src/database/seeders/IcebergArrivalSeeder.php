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
        IcebergArrival::factory()->count(5)->create();
    }
}
