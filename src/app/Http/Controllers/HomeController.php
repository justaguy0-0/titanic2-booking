<?php

namespace App\Http\Controllers;

use App\Models\Entertainment;

class HomeController extends Controller
{
    public function index()
    {
        // Получаем только цены для нужных развлечений
        $prices = [
            'Вечерний ужин на палубе' => Entertainment::where('name', 'Вечерний ужин на палубе')->first()?->price ?? 0,
            'Спа-процедуры' => Entertainment::where('name', 'Спа-процедуры')->first()?->price ?? 0,
            'Концерт оркестра' => Entertainment::where('name', 'Концерт оркестра')->first()?->price ?? 0,
            'Детский клуб "Морские приключения"' => Entertainment::where('name', 'Детский клуб "Морские приключения"')->first()?->price ?? 0,
            'Шоу воздушных акробатов' => Entertainment::where('name', 'Шоу воздушных акробатов')->first()?->price ?? 0,
            'Танцевальный вечер в бальном зале' => Entertainment::where('name', 'Танцевальный вечер в бальном зале')->first()?->price ?? 0,
            'Йога для самых здоровых' => Entertainment::where('name', 'Йога для самых здоровых')->first()?->price ?? 0,
        ];

//        $prices = Entertainment::whereIn([
//            'Вечерний ужин на палубе',
//            'Спа-процедуры'
//        ])->get();

        return view("home", compact("prices"));
    }
}
