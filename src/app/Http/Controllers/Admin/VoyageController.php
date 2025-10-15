<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IcebergArrival;
use App\Models\PlaceDeparture;
use App\Models\Voyage;
use Illuminate\Http\Request;

class VoyageController extends Controller
{
    /**
     * Display a listing of the resource.
     * Показать список всех путешествий
     */
    public function index()
    {
        // Получаем путешествия с подгруженными связями (оптимизация)
        $voyages = Voyage::with(['placeDeparture', 'icebergArrival'])
            ->orderBy('departure_date', 'desc') // ✅ Сортировка по дате
            ->paginate(10);

        return view('admin.voyages.index', compact('voyages'));
    }

    /**
     * Show the form for creating a new resource.
     * Показать форму создания
     */
    public function create()
    {
        // Получаем все места отправления и прибытия для выпадающих списков
        $placeDepartures = PlaceDeparture::orderBy('name')->get();
        $icebergArrivals = IcebergArrival::orderBy('name')->get();

        return view('admin.voyages.create', compact('placeDepartures', 'icebergArrivals'));
    }

    /**
     * Store a newly created resource in storage.
     * Сохранить новое путешествие
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'place_departure' => 'required|exists:place_departures,id',
            'iceberg_arrival' => 'required|exists:iceberg_arrivals,id',
            'departure_date' => 'required|date|after_or_equal:today', // ✅ Не раньше сегодня
            'arrival_date' => 'required|date|after:departure_date',
            'travel_time' => 'required|integer|min:0',
            'base_price' => 'required|numeric|min:0|max:99999999.99',
        ], [
            // ✅ Пользовательские сообщения об ошибках
            'departure_date.after_or_equal' => 'Дата отправления не может быть в прошлом',
            'arrival_date.after' => 'Дата прибытия должна быть после даты отправления',
            'place_departure.required' => 'Выберите место отправления',
            'iceberg_arrival.required' => 'Выберите место прибытия',
        ]);

        Voyage::create($validated);

        return redirect()
            ->route('admin.voyages.index')
            ->with('success', 'Путешествие успешно добавлено!');
    }

    /**
     * Display the specified resource.
     * Показать одно путешествие
     */
    public function show(Voyage $voyage)
    {
        $voyage->load(['placeDeparture', 'icebergArrival']);
        return view('admin.voyages.show', compact('voyage'));
    }

    /**
     * Show the form for editing the specified resource.
     * Показать форму редактирования
     */
    public function edit(Voyage $voyage)
    {
        $placeDepartures = PlaceDeparture::orderBy('name')->get();
        $icebergArrivals = IcebergArrival::orderBy('name')->get();
        
        // Загружаем связанные данные
        $voyage->load(['placeDeparture', 'icebergArrival']);

        return view('admin.voyages.edit', compact('voyage', 'placeDepartures', 'icebergArrivals'));
    }

    /**
     * Update the specified resource in storage.
     * Обновить путешествие
     */
    public function update(Request $request, Voyage $voyage)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'place_departure' => 'required|exists:place_departures,id',
            'iceberg_arrival' => 'required|exists:iceberg_arrivals,id',
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

    /**
     * Remove the specified resource from storage.
     * Удалить путешествие
     */
    public function destroy(Voyage $voyage)
    {
        // ✅ ДОБАВЛЕНО: Проверка перед удалением
        // Если у вас есть модель Booking (бронирования)
        // if ($voyage->bookings()->count() > 0) {
        //     return redirect()
        //         ->back()
        //         ->with('error', 'Нельзя удалить путешествие с активными бронированиями!');
        // }

        // Проверка: нельзя удалить путешествие, которое уже началось
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