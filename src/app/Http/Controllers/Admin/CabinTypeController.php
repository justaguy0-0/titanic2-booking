<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CabinType;
use Illuminate\Http\Request;

class CabinTypeController extends Controller
{
    public function index()
    {
        $cabinTypes = CabinType::orderBy('name')->paginate(10);
        return view('admin.cabin-types.index', compact('cabinTypes'));
    }

    public function create()
    {
        return view('admin.cabin-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:cabin_types,name',
            'description' => 'nullable|string',
        ]);

        CabinType::create($validated);

        return redirect()->route('admin.cabin-types.index')->with('success', 'Тип каюты успешно добавлен.');
    }

    public function edit(CabinType $cabinType)
    {
        return view('admin.cabin-types.edit', compact('cabinType'));
    }

    public function update(Request $request, CabinType $cabinType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:cabin_types,name,' . $cabinType->id,
            'description' => 'nullable|string',
        ]);

        $cabinType->update($validated);

        return redirect()->route('admin.cabin-types.index')->with('success', 'Тип каюты успешно обновлён.');
    }

    public function destroy(CabinType $cabinType)
    {
        if ($cabinType->tickets()->exists()) {
            return redirect()->route('admin.cabin-types.index')
                ->with('error', 'Нельзя удалить тип каюты, связанный с билетами.');
        }

        $cabinType->delete();
        return redirect()->route('admin.cabin-types.index')->with('success', 'Тип каюты успешно удалён.');
    }
}
