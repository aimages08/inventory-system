<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::withCount('products')->latest()->paginate(15);
        return view('units.index', compact('units'));
    }

    public function create()
    {
        return view('units.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:191|unique:units,name',
            'short_name' => 'required|string|max:20',
            'is_active'  => 'boolean',
        ]);

        Unit::create($data);

        return redirect()->route('units.index')->with('success', 'Unit created.');
    }

    public function show(Unit $unit)
    {
        $unit->load('products');
        return view('units.show', compact('unit'));
    }

    public function edit(Unit $unit)
    {
        return view('units.edit', compact('unit'));
    }

    public function update(Request $request, Unit $unit)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:191|unique:units,name,' . $unit->id,
            'short_name' => 'required|string|max:20',
            'is_active'  => 'boolean',
        ]);

        $unit->update($data);

        return redirect()->route('units.index')->with('success', 'Unit updated.');
    }

    public function destroy(Unit $unit)
    {
        if ($unit->products()->count() > 0) {
            return back()->with('error', 'Cannot delete: unit is used by products.');
        }
        $unit->delete();
        return redirect()->route('units.index')->with('success', 'Unit deleted.');
    }
}