<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Voyage;
use App\Models\CabinType;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['voyage', 'cabinType', 'orderItems.order']);

        $sortField = $request->get('sort', 'id');
        $sortDirection = $request->get('direction', 'desc');

        $allowedSorts = ['id', 'number', 'price', 'status', 'created_at', 'updated_at'];

        if (!in_array($sortField, $allowedSorts)) {
            $sortField = 'id';
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        $query->orderBy($sortField, $sortDirection);

        $tickets = $query->paginate(15)->appends($request->query());

        return view('admin.tickets.index', compact('tickets'));
    }

    public function create()
    {
        $voyages = Voyage::all();
        $cabinTypes = CabinType::orderBy('name')->get();
        return view('admin.tickets.create', compact('voyages', 'cabinTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'voyages_id' => 'required|exists:voyages,id',
            'cabin_type_id' => 'required|exists:cabin_types,id',
            'number' => 'required|string|max:20|unique:tickets,number',
            'price' => 'required|numeric|min:0|max:99999999.99',
            'status' => 'required|in:Доступно',
        ]);

        Ticket::create($validated);

        return redirect()->route('admin.tickets.index')->with('success', 'Билет успешно добавлен.');
    }

    public function edit(Ticket $ticket)
    {
        $voyages = Voyage::all();
        $cabinTypes = CabinType::orderBy('name')->get();
        return view('admin.tickets.edit', compact('ticket', 'voyages', 'cabinTypes'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $rules = [
            'voyages_id' => 'required|exists:voyages,id',
            'cabin_type_id' => 'required|exists:cabin_types,id',
            'number' => 'required|string|max:20|unique:tickets,number,' . $ticket->id,
            'price' => 'required|numeric|min:0|max:99999999.99',
        ];

        if ($ticket->orderItems->isNotEmpty()) {
            $rules['status'] = 'required|in:' . $ticket->status;
        } else {
            $rules['status'] = 'required|in:Доступно,Забронировано,Продано';
        }

        $validated = $request->validate($rules);

        $ticket->update($validated);

        return redirect()->route('admin.tickets.index')->with('success', 'Билет успешно обновлён.');
    }

    public function destroy(Ticket $ticket)
    {
        if ($ticket->orderItems->isNotEmpty()) {
            return redirect()->route('admin.tickets.index')->with('error', 'Нельзя удалить билет, связанный с заказом #' . $ticket->orderItems->first()->order_id);
        }

        $ticket->delete();
        return redirect()->route('admin.tickets.index')->with('success', 'Билет успешно удалён.');
    }

    public function show(Ticket $ticket)
    {
        $ticket->load([
            'voyage.departurePlace',
            'voyage.arrivalPlace',
            'cabinType',
            'orderItems.order.user'
        ]);

        return view('admin.tickets.show', compact('ticket'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        return Ticket::with(['voyage', 'cabinType'])
            ->where('status', 'Доступно')
            ->when($query, function ($q) use ($query) {
                $q->where('number', 'like', "%{$query}%")
                    ->orWhereHas('voyage', fn($v) => $v->where('name', 'like', "%{$query}%"))
                    ->orWhereHas('cabinType', fn($c) => $c->where('name', 'like', "%{$query}%"));
            })
            ->take(50)
            ->get()
            ->map(fn($t) => [
                'id' => $t->id,
                'number' => $t->number,
                'voyage_name' => $t->voyage?->name ?? '—',
                'cabin_type_name' => $t->cabinType?->name ?? '—',
                'price' => $t->price,
                'price_formatted' => number_format($t->price, 0, '', ' ')
            ]);
    }
}
