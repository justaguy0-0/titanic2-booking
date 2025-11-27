<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Place;
use App\Models\Voyage;
use Illuminate\Http\Request;

class VoyageController extends Controller
{
    public function index(Request $request)
    {
        // Базовый query с подсчетом билетов
        $query = Voyage::with(['departurePlace', 'arrivalPlace'])
            ->withCount([
                'tickets',
                'tickets as available_tickets_count' => function ($query) {
                    $query->where('status', 'Доступно');
                }
            ]);

        // Фильтрация по месту отправления
        if ($request->filled('departure_place')) {
            $query->where('departure_place_id', $request->departure_place);
        }

        // Фильтрация по месту прибытия
        if ($request->filled('arrival_place')) {
            $query->where('arrival_place_id', $request->arrival_place);
        }

        // Фильтрация по статусу рейса
        if ($request->filled('status')) {
            $now = now();
            switch ($request->status) {
                case 'upcoming':
                    $query->where('departure_date', '>', $now);
                    break;
                case 'in_progress':
                    $query->where('departure_date', '<=', $now)
                        ->where('arrival_date', '>=', $now);
                    break;
                case 'completed':
                    $query->where('arrival_date', '<', $now);
                    break;
            }
        }

        // Фильтрация по диапазону дат
        if ($request->filled('date_from')) {
            $query->where('departure_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('departure_date', '<=', $request->date_to . ' 23:59:59');
        }

        // Сортировка
        $sortField = $request->get('sort', 'departure_date');
        $sortDirection = $request->get('direction', 'desc');

        // Валидация поля сортировки (защита от SQL injection)
        $allowedSorts = ['id', 'name', 'departure_date', 'arrival_date', 'base_price'];
        if (!in_array($sortField, $allowedSorts)) {
            $sortField = 'departure_date';
        }

        // Валидация направления
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        $query->orderBy($sortField, $sortDirection);

        // Пагинация с сохранением всех параметров
        $voyages = $query->paginate(15)->appends($request->except('page'));

        // Данные для фильтров
        $departurePlaces = Place::where('type', 'departure')->orderBy('name')->get();
        $arrivalPlaces = Place::where('type', 'arrival')->orderBy('name')->get();

        return view('admin.voyages.index', compact(
            'voyages',
            'departurePlaces',
            'arrivalPlaces'
        ));
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

        // Пагинация билетов (например, по 10 на страницу)
        $tickets = $voyage->tickets()->paginate(20);

        // Получаем счетчики для статистики
        $voyage->loadCount([
            'tickets',
            'tickets as available_tickets_count' => function ($query) {
                $query->where('status', 'Доступно');
            }
        ]);

        return view('admin.voyages.show', compact('voyage', 'tickets'));
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
        // Проверка: нельзя удалить рейс, который уже начался
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
