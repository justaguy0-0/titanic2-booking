<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\Voyage;
use App\Models\CabinType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        if (Voyage::count() === 0 || CabinType::count() === 0) {
            throw new \Exception('Рейсы или типы кают отсутствуют');
        }

        if (Ticket::count() < 100) {
            Ticket::factory()->count(100 - Ticket::count())->create();
        }
    }
}
