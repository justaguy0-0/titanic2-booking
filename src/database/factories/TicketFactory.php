<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Model\Ticket;
use App\Models\Voyage;
use App\Models\CabinType;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    protected $model = \App\Models\Ticket::class;

    public function definition(): array
    {
        return [
            'voyages_id' => Voyage::inRandomOrder()->first()->id ?? Voyage::factory()->create()->id,
            'cabin_type_id' => CabinType::inRandomOrder()->first()->id ?? CabinType::factory()->create()->id,
            'number' => $this->faker->unique()->regexify('TIT[0-9]{4}'),
            'price' => $this->faker->randomFloat(2, 50, 5000),
            'status' => 'Доступно',
        ];
    }
}
