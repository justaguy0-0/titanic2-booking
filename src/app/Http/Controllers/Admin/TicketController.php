<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Voyage;
use App\Models\CabinType;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Ticket::with(['voyage', 'cabinType', 'orderItems'])->paginate(10);
        return view('admin.tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $voyages = Voyage::all();
        $cabinTypes = CabinType::orderBy('name')->get();
        return view('admin.tickets.create', compact('voyages', 'cabinTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'voyages_id' => 'required|exists:voyages,id',
            'cabin_type_id' => 'required|exists:cabin_types,id',
            'number' => 'required|string|max:20|unique:tickets,number',
            'price' => 'required|numeric|min:0|max:99999999.99',
            'status' => 'required|in:Доступно',
        ]);

        // Проверяем, что цена соответствует base_price типа каюты
        $cabinType = CabinType::findOrFail($validated['cabin_type_id']);
        if (round($validated['price'], 2) !== round($cabinType->base_price, 2)) {
            return back()->withErrors(['price' => 'Цена должна соответствовать базовой цене типа каюты.'])->withInput();
        }

        Ticket::create($validated);

        return redirect()->route('admin.tickets.index')->with('success', 'Билет успешно добавлен.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        $voyages = Voyage::all();
        $cabinTypes = CabinType::orderBy('name')->get();
        return view('admin.tickets.edit', compact('ticket', 'voyages', 'cabinTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        $rules = [
            'voyages_id' => 'required|exists:voyages,id',
            'cabin_type_id' => 'required|exists:cabin_types,id',
            'number' => 'required|string|max:20|unique:tickets,number,' . $ticket->id,
            'price' => 'required|numeric|min:0|max:99999999.99',
        ];

        // Если билет связан с заказом, статус нельзя изменить
        if ($ticket->orderItems->isNotEmpty()) {
            $rules['status'] = 'required|in:' . $ticket->status;
        } else {
            $rules['status'] = 'required|in:Доступно,Забронировано,Продано';
        }

        $validated = $request->validate($rules);

        // Проверяем, что цена соответствует base_price типа каюты
        $cabinType = CabinType::findOrFail($validated['cabin_type_id']);
        if (round($validated['price'], 2) !== round($cabinType->base_price, 2)) {
            return back()->withErrors(['price' => 'Цена должна соответствовать базовой цене типа каюты.'])->withInput();
        }

        $ticket->update($validated);

        return redirect()->route('admin.tickets.index')->with('success', 'Билет успешно обновлён.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        if ($ticket->orderItems->isNotEmpty()) {
            return redirect()->route('admin.tickets.index')->with('error', 'Нельзя удалить билет, связанный с заказом #' . $ticket->orderItems->first()->order_id);
        }

        $ticket->delete();
        return redirect()->route('admin.tickets.index')->with('success', 'Билет успешно удалён.');
    }
}
