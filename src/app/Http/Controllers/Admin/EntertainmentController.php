<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entertainment;
use Illuminate\Http\Request;
use PharIo\Manifest\ElementCollection;

class EntertainmentController extends Controller
{
    public function index()
    {
        $entertainments = Entertainment::paginate(10);
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
