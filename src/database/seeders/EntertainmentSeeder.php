<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Entertainment;

class EntertainmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entertainments = [
            [
                'name' => 'Вечерний ужин на палубе',
                'description' => 'Романтический ужин под звёздами с морепродуктами и винами премиум-класса.',
                'image' => 'rest.jpg',
                'category' => 'Питание',
                'price' => 5000,
            ],
            [
                'name' => 'Спа-процедуры',
                'description' => 'Премиум спа-комплекс с талассотерапией, массажем и косметическими процедурами.',
                'image' => 'spa.jpg',
                'category' => 'Релакс',
                'price' => 8000,
            ],
            [
                'name' => 'Концерт оркестра',
                'description' => 'Живая музыка в исполнении симфонического оркестра. Классические и современные произведения.',
                'image' => 'mus.jpg',
                'category' => 'Культурное',
                'price' => 0,
            ],
            [
                'name' => 'Детский клуб "Морские приключения"',
                'description' => 'Анимационная программа, мастер-классы, игры и развлечения для детей всех возрастов под присмотром профессиональных воспитателей.',
                'image' => 'child.jpg',
                'category' => 'Детское',
                'price' => 0,
            ],
            [
                'name' => 'Шоу воздушных акробатов',
                'description' => 'Захватывающее представление профессиональных акробатов под куполом главного атриума. Огни, музыка и невероятные трюки.',
                'image' => 'akro.jpg',
                'category' => 'Шоу',
                'price' => 3500,
            ],
            [
                'name' => 'Танцевальный вечер в бальном зале',
                'description' => 'Роскошный бал в стиле 20-х годов. Живой джаз-бэнд, профессиональные танцоры и уроки исторических танцев.',
                'image' => 'dance.webp',
                'category' => 'Вечеринка',
                'price' => 4000,
            ],
            [
                'name' => 'Йога для самых здоровых',
                'description' => 'Дневные занятия на свежем воздухе. Под шум волн.',
                'image' => 'ioga.jpg',
                'category' => 'Спорт',
                'price' => 2000,
            ],
        ];

        foreach ($entertainments as $entertainment) {
            Entertainment::firstOrCreate(
                ['name' => $entertainment['name']],
                $entertainment 
            );
        }
    }
}
