<?php

namespace App\Http\Controllers;

use App\Models\Entertainment;

class HomeController extends Controller
{
    public function index()
    {
        $entertainmentForHome = Entertainment::whereIn('name', [
            'Вечерний ужин на палубе',
            'Спа-процедуры',
            'Концерт оркестра',
            'Детский клуб "Морские приключения"',
            'Шоу воздушных акробатов',
            'Танцевальный вечер в бальном зале',
            'Йога для самых здоровых'
        ])->get();

        return view("home", compact("entertainmentForHome"));
    }
    
}
