<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entertainment;
use Illuminate\Http\Request;
use PharIo\Manifest\ElementCollection;

class EntertainmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $entertainments = Entertainment::paginate(10);
        return view('admin.entertainments.index', compact('entertainments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.entertainments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'price' => 'required|numeric|min:0|max:99999999.99',
            ]);

            Entertainment::create($validated);

            return redirect()->route('admin.entertainments.index')->with('success', 'Развлечение успешно добавлено.');
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
    public function edit(Entertainment $entertainment)
    {
        return view('admin.entertainments.edit', compact('entertainment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Entertainment $entertainment)
    {
        $validated = $request->validate([
                'name' => 'required|string|max:100',
                'price' => 'required|numeric|min:0|max:99999999.99',
            ]);

            $entertainment->update($validated);

            return redirect()->route('admin.entertainments.index')->with('success', 'Развлечение успешно обновлено.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Entertainment $entertainment)
    {
        $entertainment->delete();
            return redirect()->route('admin.entertainments.index')->with('success', 'Развлечение успешно удалено.');
    }
}
