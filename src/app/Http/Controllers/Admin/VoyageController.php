<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Place;
use App\Models\Voyage;
use Illuminate\Http\Request;

class VoyageController extends Controller
{
    public function index()
    {
        // Изменено: Загружаем новые relations (Place с типами)
        $voyages = Voyage::with(['departurePlace', 'arrivalPlace'])
            ->orderBy('departure_date', 'desc')
            ->paginate(10);

        return view('admin.voyages.index', compact('voyages'));
    }

    public function create()
    {
        $departures = Place::departure()->orderBy('name')->get();
        $arrivals = Place::arrival()->orderBy('name')->get();

        return view('admin.voyages.create', compact('departures', 'arrivals'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'departure_place_id' => 'required|exists:places,id',
            'arrival_place_id' => 'required|exists:places,id',
            'departure_date' => 'required|date|after_or_equal:today',
            'arrival_date' => 'required|date|after:departure_date',
            'travel_time' => 'required|integer|min:0',
            'base_price' => 'required|numeric|min:0|max:99999999.99',
        ], [
            'departure_date.after_or_equal' => 'Дата отправления не может быть в прошлом',
            'arrival_date.after' => 'Дата прибытия должна быть после даты отправления',
            'departure_place_id.required' => 'Выберите место отправления',
            'arrival_place_id.required' => 'Выберите место прибытия',
        ]);

        Voyage::create($validated);

        return redirect()
            ->route('admin.voyages.index')
            ->with('success', 'Путешествие успешно добавлено!');
    }

    public function show(Voyage $voyage)
    {
        $voyage->load(['departurePlace', 'arrivalPlace']);
        return view('admin.voyages.show', compact('voyage'));
    }

    public function edit(Voyage $voyage)
    {
        $departures = Place::departure()->orderBy('name')->get();
        $arrivals = Place::arrival()->orderBy('name')->get();

        // Загружаем связанные данные
        $voyage->load(['departurePlace', 'arrivalPlace']);

        return view('admin.voyages.edit', compact('voyage', 'departures', 'arrivals'));
    }

    public function update(Request $request, Voyage $voyage)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'departure_place_id' => 'required|exists:places,id',
            'arrival_place_id' => 'required|exists:places,id',
            'departure_date' => 'required|date',
            'arrival_date' => 'required|date|after:departure_date',
            'travel_time' => 'required|integer|min:0',
            'base_price' => 'required|numeric|min:0|max:99999999.99',
        ], [
            'arrival_date.after' => 'Дата прибытия должна быть после даты отправления',
        ]);

        $voyage->update($validated);

        return redirect()
            ->route('admin.voyages.index')
            ->with('success', 'Путешествие успешно обновлено!');
    }

    public function destroy(Voyage $voyage)
    {
        if ($voyage->departure_date && $voyage->departure_date->isPast()) {
            return redirect()
                ->back()
                ->with('error', 'Нельзя удалить путешествие, которое уже началось или завершилось!');
        }

        $voyage->delete();

        return redirect()
            ->route('admin.voyages.index')
            ->with('success', 'Путешествие успешно удалено!');
    }
}
