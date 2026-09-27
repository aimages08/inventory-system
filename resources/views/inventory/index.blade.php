@extends('layouts.app')

@section('title', 'Inventory')

@section('content')

<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Inventory</h1>
        <p class="text-sm text-slate-500">Current stock across all products</p>
    </div>
    <a href="{{ route('inventory.movements') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-sm font-medium">
        <i class="bi bi-clock-history"></i> Stock History
    </a>
</div>

{{-- Summary --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-lg bg-blue-500 text-white flex items-center justify-center text-xl"><i class="bi bi-box"></i></div>
        <div>
            <div class="text-xs text-slate-500">Total Products</div>
            <div class="text-2xl font-bold text-slate-800">{{ $summary['total_products'] }}</div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-lg bg-emerald-500 text-white flex items-center justify-center text-xl"><i class="bi bi-currency-dollar"></i></div>
        <div>
            <div class="text-xs text-slate-500">Stock Value</div>
            <div class="text-2xl font-bold text-slate-800">{{ number_format($summary['stock_value'], 2) }}</div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-lg bg-amber-500 text-white flex items-center justify-center text-xl"><i class="bi bi-exclamation-triangle"></i></div>
        <div>
            <div class="text-xs text-slate-500">Low Stock</div>
            <div class="text-2xl font-bold text-slate-800">{{ $summary['low_stock'] }}</div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-lg bg-red-500 text-white flex items-center justify-center text-xl"><i class="bi bi-x-octagon"></i></div>
        <div>
            <div class="text-xs text-slate-500">Out of Stock</div>
            <div class="text-2xl font-bold text-slate-800">{{ $summary['out_of_stock'] }}</div>
        </div>
    </div>
</div>

{{-- Filters --}}
<form method="GET" class="bg-white rounded-xl shadow-sm p-4 mb-4 grid grid-cols-1 md:grid-cols-4 gap-3">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or SKU..."
           class="border border-slate-200 rounded-lg px-3 py-2 text-sm md:col-span-2">
    <select name="status" class="border border-slate-200 rounded-lg px-3 py-2 text-sm">
        <option value="">All Status</option>
        <option value="in"  @selected(request('status') === 'in')>In Stock</option>
        <option value="low" @selected(request('status') === 'low')>Low Stock</option>
        <option value="out" @selected(request('status') === 'out')>Out of Stock</option>
    </select>
    <div class="flex gap-2">
        <button class="flex-1 px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-sm"><i class="bi bi-search"></i></button>
        <a href="{{ route('inventory.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg text-sm">Reset</a>
    </div>
</form>

{{-- Table --}}
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-600 text-left">
                <tr>
                    <th class="px-4 py-3">Product</th>
                    <th class="px-4 py-3">SKU</th>
                    <th class="px-4 py-3">Category</th>
                    <th class="px-4 py-3 text-right">Stock</th>
                    <th class="px-4 py-3 text-right">Min</th>
                    <th class="px-4 py-3 text-right">Value</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($products as $product)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $product->name }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $product->sku }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $product->category->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-right font-medium">{{ $product->stock_quantity }} {{ $product->unit->short_name ?? '' }}</td>
                        <td class="px-4 py-3 text-right text-slate-500">{{ $product->minimum_stock }}</td>
                        <td class="px-4 py-3 text-right">{{ number_format($product->stock_quantity * $product->purchase_price, 2) }}</td>
                        <td class="px-4 py-3">
                            @if($product->is_out_of_stock)
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">Out of Stock</span>
                            @elseif($product->is_low_stock)
                                <span class="px-2 py-1 text-xs rounded-full bg-amber-100 text-amber-700">Low Stock</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">In Stock</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('inventory.adjust.form', $product) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-xs font-medium">
                                <i class="bi bi-sliders"></i> Adjust
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-4 py-12 text-center text-slate-400">
                        <i class="bi bi-boxes text-4xl block mb-2"></i> No products found.
                    </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $products->links() }}</div>

@endsection