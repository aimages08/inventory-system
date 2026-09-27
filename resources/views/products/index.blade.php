@extends('layouts.app')

@section('title', 'Products')

@section('content')

<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Products</h1>
        <p class="text-sm text-slate-500">Manage your product catalog</p>
    </div>
    <a href="{{ route('products.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">
        <i class="bi bi-plus-lg"></i> Add Product
    </a>
</div>

{{-- Filters --}}
<form method="GET" class="bg-white rounded-xl shadow-sm p-4 mb-4 grid grid-cols-1 md:grid-cols-4 gap-3">
    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="Search name, SKU, barcode..."
           class="border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">

    <select name="category_id" class="border border-slate-200 rounded-lg px-3 py-2 text-sm">
        <option value="">All Categories</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
        @endforeach
    </select>

    <select name="status" class="border border-slate-200 rounded-lg px-3 py-2 text-sm">
        <option value="">All Status</option>
        <option value="low" @selected(request('status') === 'low')>Low Stock</option>
        <option value="out" @selected(request('status') === 'out')>Out of Stock</option>
    </select>

    <div class="flex gap-2">
        <button class="flex-1 px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-sm">
            <i class="bi bi-search me-1"></i> Filter
        </button>
        <a href="{{ route('products.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg text-sm">
            Reset
        </a>
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
                    <th class="px-4 py-3">Price</th>
                    <th class="px-4 py-3">Stock</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($products as $product)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                @if($product->image)
                                    <img src="{{ asset('storage/'.$product->image) }}" class="w-10 h-10 rounded-lg object-cover">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400">
                                        <i class="bi bi-box"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="font-medium text-slate-800">{{ $product->name }}</div>
                                    <div class="text-xs text-slate-400">{{ $product->brand->name ?? '—' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $product->sku }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $product->category->name ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <div class="font-medium">{{ number_format($product->selling_price, 2) }}</div>
                            <div class="text-xs text-slate-400">Buy: {{ number_format($product->purchase_price, 2) }}</div>
                        </td>
                        <td class="px-4 py-3">{{ $product->stock_quantity }} {{ $product->unit->short_name ?? '' }}</td>
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
                            <div class="inline-flex items-center gap-1">
                                <a href="{{ route('products.show', $product) }}" class="p-2 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('products.edit', $product) }}" class="p-2 text-slate-500 hover:text-amber-600 hover:bg-amber-50 rounded">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete this product?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button class="p-2 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-slate-400">
                            <i class="bi bi-inbox text-4xl block mb-2"></i>
                            No products found. Click <strong>Add Product</strong> to create one.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $products->links() }}
</div>

@endsection