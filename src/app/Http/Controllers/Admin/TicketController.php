<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Voyage;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Ticket::with('voyage')->paginate(10);
        return view('admin.tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $voyages = Voyage::all();
        return view('admin.tickets.create', compact('voyages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'voyages_id' => 'required|exists:voyages,id',
            'type' => 'required|string|max:20',
            'number' => 'required|string|max:20|unique:tickets,number',
            'price' => 'required|numeric|min:0|max:99999999.99',
            'status' => 'required|string|in:Доступно,Забронировано,Продано',
        ]);

        Ticket::create($validated);

        return redirect()->route('admin.tickets.index')->with('success', 'Билет успешно добавлен.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        $voyages = Voyage::all();
        return view('admin.tickets.edit', compact('ticket', 'voyages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'voyages_id' => 'required|exists:voyages,id',
            'type' => 'required|string|max:20',
            'number' => 'required|string|max:20|unique:tickets,number,' . $ticket->id,
            'price' => 'required|numeric|min:0|max:99999999.99',
            'status' => 'required|string|in:Доступно,Забронировано,Продано',
        ]);

        $ticket->update($validated);

        return redirect()->route('admin.tickets.index')->with('success', 'Билет успешно обновлён.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        return redirect()->route('admin.tickets.index')->with('success', 'Билет успешно удалён.');
    }
}
