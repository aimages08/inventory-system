<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::withCount('products')->latest()->paginate(15);
        return view('brands.index', compact('brands'));
    }

    public function create()
    {
        return view('brands.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:191|unique:brands,name',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(4);
        Brand::create($data);

        return redirect()->route('brands.index')->with('success', 'Brand created.');
    }

    public function show(Brand $brand)
    {
        $brand->load('products');
        return view('brands.show', compact('brand'));
    }

    public function edit(Brand $brand)
    {
        return view('brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:191|unique:brands,name,' . $brand->id,
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        $brand->update($data);

        return redirect()->route('brands.index')->with('success', 'Brand updated.');
    }

    public function destroy(Brand $brand)
    {
        if ($brand->products()->count() > 0) {
            return back()->with('error', 'Cannot delete: brand has products.');
        }
        $brand->delete();
        return redirect()->route('brands.index')->with('success', 'Brand deleted.');
    }
}