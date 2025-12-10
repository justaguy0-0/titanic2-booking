<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupportController extends Controller
{
    /**
     * Показать страницу поддержки
     */
    public function index()
    {
        return view('support');
    }
}
