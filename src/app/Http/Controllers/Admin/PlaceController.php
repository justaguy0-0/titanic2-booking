<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Place;
use Illuminate\Http\Request;


class PlaceController extends Controller
{

    public function index(Request $request)
    {
        $type = $request->query('type', 'departure');

        if (!in_array($type, ['departure', 'arrival'])) {
            $type = 'departure';
        }

        $query = Place::where('type', $type);

        $sortField = $request->get('sort', 'id');
        $sortDirection = $request->get('direction', 'desc');

        $allowedSorts = ['id', 'name', 'type', 'created_at'];
        if (!in_array($sortField, $allowedSorts)) {
            $sortField = 'id';
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        $query->orderBy($sortField, $sortDirection);

        $places = $query->paginate(15)->appends($request->query());

        return view('admin.places.index', compact('places', 'type'));
    }

    public function create(Request $request)
    {
        $type = $request->query('type', 'departure');
        return view('admin.places.create', compact('type'));
    }

    public function store(Request $request)
    {
        $type = $request->input('type');
        $validated = $request->validate([
            'name' => "required|string|max:100|unique:places,name,NULL,id,type,$type",
            'type' => 'required|in:departure,arrival',
        ]);

        Place::create($validated);

        return redirect()->route('admin.places.index', ['type' => $type])->with('success', 'Место добавлено!');
    }

    public function edit(Place $place)
    {
        return view('admin.places.edit', compact('place'));
    }

    public function update(Request $request, Place $place)
    {
        $validated = $request->validate([
            'name' => "required|string|max:100|unique:places,name,{$place->id},id,type,{$place->type}",
            'type' => 'required|in:departure,arrival',
        ]);

        $place->update($validated);

        return redirect()->route('admin.places.index', ['type' => $place->type])->with('success', 'Обновлено!');
    }

    public function destroy(Place $place)
    {
        $place->delete();
        return redirect()->route('admin.places.index', ['type' => $place->type])->with('success', 'Удалено!');
    }
}
