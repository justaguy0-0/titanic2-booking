<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlaceDeparture;
use Illuminate\Http\Request;

class PlaceDepartureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $places = PlaceDeparture::paginate(10);
        return view('admin.place-departures.index', compact('places'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.place-departures.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' =>'required|string|max:100|unique:place_departures'
        ]);
        PlaceDeparture::create($validated);
        return redirect()->route('admin.place-departures.index')->with('success', 'Место добавлено!');
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
    public function edit(PlaceDeparture $placeDeparture)
    {
        return view('admin.place-departures.edit', compact('placeDeparture'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PlaceDeparture $placeDeparture)
    {
        $validated = $request->validate([
        'name' => 'required|string|max:100|unique:place_departures,name,' . $placeDeparture->id,
        ]);

        $placeDeparture->update($validated);

        return redirect()->route('admin.place-departures.index')->with('success', 'Обновлено!');


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PlaceDeparture $placeDeparture)
    {
        $placeDeparture->delete();

        return redirect()->route('admin.place-departures.index')->with('success', 'Удалено!');
    }
}
