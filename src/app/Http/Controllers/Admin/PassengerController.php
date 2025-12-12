<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Passenger;
use Illuminate\Http\Request;

class PassengerController extends Controller
{
    public function index(Request $request)
    {
        $query = Passenger::query()
            ->with(['orderItem.order.user', 'orderItem.ticket.voyage']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('passport_number', 'like', "%{$search}%")
                    ->orWhere('passport_series', 'like', "%{$search}%");
            });
        }

        $sortField = $request->get('sort', 'id');
        $sortDirection = $request->get('direction', 'desc');
        $allowed = ['id', 'first_name', 'last_name', 'birth_date', 'created_at'];
        if (!in_array($sortField, $allowed)) $sortField = 'id';
        if (!in_array($sortDirection, ['asc', 'desc'])) $sortDirection = 'desc';

        $passengers = $query->orderBy($sortField, $sortDirection)->paginate(20);

        return view('admin.passengers.index', compact('passengers'));
    }

    public function show(Passenger $passenger)
    {
        $passenger->load([
            'orderItem.order.user',
            'orderItem.ticket.voyage.departurePlace',
            'orderItem.ticket.voyage.arrivalPlace',
            'orderItem.ticket.cabinType'
        ]);

        return view('admin.passengers.show', compact('passenger'));
    }

    public function edit(Passenger $passenger)
    {
        return view('admin.passengers.edit', compact('passenger'));
    }

    public function update(Request $request, Passenger $passenger)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'birth_date' => 'required|date|before:today',
            'passport_series' => 'required|string|max:10',
            'passport_number' => 'required|string|max:20',
            'citizenship' => 'required|string|max:100',
        ]);

        $birthDate = new \DateTime($validated['birth_date']);
        $today = new \DateTime();
        $age = $today->diff($birthDate)->y;

        $discountPercent = 0;
        if ($age < 12) {
            $discountPercent = 20;
        }

        $passenger->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'birth_date' => $validated['birth_date'],
            'passport_series' => $validated['passport_series'],
            'passport_number' => $validated['passport_number'],
            'citizenship' => $validated['citizenship'],
            'age' => $age,
            'discount_percent' => $discountPercent,
        ]);

        return redirect()->route('admin.passengers.show', $passenger)
            ->with('success', "Данные пассажира {$passenger->full_name} обновлены");
    }

    public function destroy(Passenger $passenger)
    {
        $name = $passenger->full_name;
        $passenger->delete();

        return back()->with('success', "Пассажир {$name} удалён");
    }
}
