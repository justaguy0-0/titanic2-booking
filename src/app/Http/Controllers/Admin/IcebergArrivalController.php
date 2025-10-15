<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IcebergArrival;
use Illuminate\Http\Request;

class IcebergArrivalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $arrivals = IcebergArrival::paginate(10);
        return view('admin.iceberg-arrivals.index', compact('arrivals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.iceberg-arrivals.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
        'name' => 'required|string|max:100|unique:iceberg_arrivals',
    ]);
        IcebergArrival::create($validated);

        return redirect()
            ->route('admin.iceberg-arrivals.index')
            ->with('success', 'Место прибытия добавлено!');
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
    public function edit(IcebergArrival $icebergArrival)
    {
        return view('admin.iceberg-arrivals.edit', compact('icebergArrival'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IcebergArrival $icebergArrival)
    {
          $validated = $request->validate([
        'name' => 'required|string|max:100|unique:iceberg_arrivals,name,' . $icebergArrival->id,                
        ]);

        $icebergArrival->update($validated);

        return redirect()->route('admin.iceberg-arrivals.index')->with('success', 'Обновлено!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IcebergArrival $icebergArrival)
    {
        $icebergArrival->delete();

        return redirect()->route('admin.iceberg-arrivals.index')->with('success', 'Удалено!');
    }
}
