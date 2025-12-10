<?php

namespace App\Http\Controllers;

use App\Models\Voyage;
use Illuminate\Http\Request;

class VoyageController extends Controller
{
    /**
     * Показать информационную страницу о круизах
     */
    public function index()
    {
        $voyages = Voyage::with(['departurePlace', 'arrivalPlace'])
            ->where('departure_date', '>=', now())
            ->orderBy('departure_date')
            ->get();

        return view('voyage', compact('voyages'));
    }
}
