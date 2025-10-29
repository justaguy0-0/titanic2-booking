<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Place;

class PlaceFactory extends Factory
{
    protected $model = Place::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(['departure', 'arrival']);
        
        $namesDeparture = [
            'Титаноград', 'Непотопинск', 'Селфи-Харбор', 
            'Северная Надежда', 'Суденск', 'Вайс-Сити', 
            'Раккун-Сити', 'Сити 17', 'Спрингфилд', 'Готэм'
        ];
        
        $namesArrival = [
            'Айсберг №1912', 'Айсберг имени Леонардо Ди Каприо', 
            'Белый Убийца Атлантики', 'Точка Невозврата', 
            'Пункт Назначения', 'Полярная Обнимашка', 
            'Ледяная Глыба Североатлантики', 'Айсберг "Непотопляемый"', 
            'Ice & Sink Co.'
        ];

        $name = $type === 'departure' 
            ? $this->faker->randomElement($namesDeparture) 
            : $this->faker->randomElement($namesArrival);

        return [
            'name' => $name,
            'type' => $type,
        ];
    }

    // Методы для явного указания типа
    public function departure()
    {
        return $this->state(function (array $attributes) {
            $namesDeparture = [
                'Титаноград', 'Непотопинск', 'Селфи-Харбор', 
                'Северная Надежда', 'Суденск', 'Вайс-Сити', 
                'Раккун-Сити', 'Сити 17', 'Спрингфилд', 'Готэм'
            ];
            
            return [
                'type' => 'departure',
                'name' => $this->faker->randomElement($namesDeparture),
            ];
        });
    }

    public function arrival()
    {
        return $this->state(function (array $attributes) {
            $namesArrival = [
                'Айсберг №1912', 'Айсберг имени Леонардо Ди Каприо', 
                'Белый Убийца Атлантики', 'Точка Невозврата', 
                'Пункт назначения', 'Полярная Обнимашка', 
                'Ледяная Глыба Североатлантики', 'Айсберг "Непотопляемый"', 
                'Ice & Sink Co.'
            ];
            
            return [
                'type' => 'arrival',
                'name' => $this->faker->randomElement($namesArrival),
            ];
        });
    }
}