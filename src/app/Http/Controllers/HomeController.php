<?php

namespace App\Http\Controllers;

use App\Models\Entertainment;

class HomeController extends Controller
{
    public function index()
    {
        // Получаем только цены для нужных развлечений
        $prices = [
            'Вечерний ужин на палубе' => Entertainment::where('name', 'Вечерний ужин на палубе')->first()->price,
            'Спа-процедуры' => Entertainment::where('name', 'Спа-процедуры')->first()->price,
            'Концерт оркестра' => Entertainment::where('name', 'Концерт оркестра')->first()->price,
            'Детский клуб "Морские приключения"' => Entertainment::where('name', 'Детский клуб "Морские приключения"')->first()->price,
            'Шоу воздушных акробатов' => Entertainment::where('name', 'Шоу воздушных акробатов')->first()->price,
            'Танцевальный вечер в бальном зале' => Entertainment::where('name', 'Танцевальный вечер в бальном зале')->first()->price,
            'Йога для самых здоровых' => Entertainment::where('name', 'Йога для самых здоровых')->first()->price,
        ];

        return view("home", compact("prices"));
    }
}