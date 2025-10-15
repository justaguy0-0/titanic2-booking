<?php

namespace Database\Seeders;

use App\Models\PlaceDeparture;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlaceDepartureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (PlaceDeparture::count() < 5) {
            PlaceDeparture::factory()->count(5)->create();
        }
    }
}
