<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CabinType;

class CabinTypeFactory extends Factory
{
    protected $model = CabinType::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Первый класс',
                'Второй класс',
                'Третий класс',
                'Люкс',
            ]),
            'description' => $this->faker->sentence(),
        ];
    }
}
