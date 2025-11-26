<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entertainment;
use Illuminate\Http\Request;
use PharIo\Manifest\ElementCollection;

class EntertainmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Entertainment::query()->withCount('orderItems');

        // === Сортировка ===
        $sortField = $request->get('sort', 'id');
        $sortDirection = $request->get('direction', 'desc');

        $allowedSorts = ['id', 'name', 'price', 'created_at', 'updated_at', 'order_items_count'];

        if (!in_array($sortField, $allowedSorts)) {
            $sortField = 'id';
            $sortDirection = 'desc';
        }

        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        // ← ИСПРАВЛЕНО: правильный orderBy
        $query->orderBy($sortField, $sortDirection);

        $entertainments = $query->paginate(15)->appends($request->query());

        return view('admin.entertainments.index', compact('entertainments'));
    }

    public function create()
    {
        return view('admin.entertainments.create');
    }

    public function store(Request $request)
    {

            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'price' => 'required|numeric|min:0|max:99999999.99',
            ]);

            Entertainment::create($validated);

            return redirect()->route('admin.entertainments.index')->with('success', 'Развлечение успешно добавлено.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(Entertainment $entertainment)
    {
        return view('admin.entertainments.edit', compact('entertainment'));
    }

    public function update(Request $request, Entertainment $entertainment)
    {
        $validated = $request->validate([
                'name' => 'required|string|max:100',
                'price' => 'required|numeric|min:0|max:99999999.99',
            ]);

            $entertainment->update($validated);

            return redirect()->route('admin.entertainments.index')->with('success', 'Развлечение успешно обновлено.');
    }

    public function destroy(Entertainment $entertainment)
    {
        $entertainment->delete();
            return redirect()->route('admin.entertainments.index')->with('success', 'Развлечение успешно удалено.');
    }
}
