<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Place;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Place>
 */
class PlaceFactory extends Factory
{
    protected $model = Place::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(['departure', 'arrival']);
        $namesDeparture = ['Титаноград', 'Непотопинск', 'Селфи-Харбор', 'Северная Надежда', 'Суденск', 'Вайс-Сити', 'Раккун-Сити', 'Сити 17', 'Спрингфилд', 'Готэм'];
        $namesArrival = ['Айсберг №1912', 'Айсберг имени Леонардо Ди Каприо', 'Белый Убийца Атлантики', 'Точка Невозврата', 'Пункт Назначения', 'Полярная Обнимашка', 'Ледяная Глыба Североатлантики', 'Айсберг "Непотопляемый"', 'Ice & Sink Co.'];

        do {
            $name = $type === 'departure' ? $this->faker->randomElement($namesDeparture) : $this->faker->randomElement($namesArrival);
        } while (Place::where('name', $name)->where('type', $type)->exists());

        return [
            'name' => $name,
            'type' => $type,
        ];
    }
}
