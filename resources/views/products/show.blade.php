@extends('layouts.app')

@section('title', $product->name)

@section('content')

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-slate-800">{{ $product->name }}</h1>
    <div class="flex gap-2">
        <a href="{{ route('products.edit', $product) }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-sm">
            <i class="bi bi-pencil me-1"></i> Edit
        </a>
        <a href="{{ route('products.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg text-sm">Back</a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

    <div class="bg-white rounded-xl shadow-sm p-5">
        @if($product->image)
            <img src="{{ asset('storage/'.$product->image) }}" class="w-full rounded-lg object-cover">
        @else
            <div class="w-full aspect-square bg-slate-100 rounded-lg flex items-center justify-center text-slate-300">
                <i class="bi bi-box text-6xl"></i>
            </div>
        @endif
    </div>

    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div><span class="text-slate-500">SKU:</span> <span class="font-medium">{{ $product->sku }}</span></div>
            <div><span class="text-slate-500">Barcode:</span> <span class="font-medium">{{ $product->barcode ?? '—' }}</span></div>
            <div><span class="text-slate-500">Category:</span> <span class="font-medium">{{ $product->category->name ?? '—' }}</span></div>
            <div><span class="text-slate-500">Brand:</span> <span class="font-medium">{{ $product->brand->name ?? '—' }}</span></div>
            <div><span class="text-slate-500">Unit:</span> <span class="font-medium">{{ $product->unit->name ?? '—' }}</span></div>
            <div><span class="text-slate-500">Status:</span> 
                <span class="font-medium {{ $product->is_active ? 'text-green-600' : 'text-red-600' }}">
                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <div><span class="text-slate-500">Purchase Price:</span> <span class="font-medium">{{ number_format($product->purchase_price, 2) }}</span></div>
            <div><span class="text-slate-500">Selling Price:</span> <span class="font-medium">{{ number_format($product->selling_price, 2) }}</span></div>
            <div><span class="text-slate-500">Tax Rate:</span> <span class="font-medium">{{ $product->tax_rate }}%</span></div>
            <div><span class="text-slate-500">Stock:</span> <span class="font-medium">{{ $product->stock_quantity }}</span></div>
            <div><span class="text-slate-500">Minimum Stock:</span> <span class="font-medium">{{ $product->minimum_stock }}</span></div>
        </div>

        @if($product->description)
            <div class="mt-5 pt-5 border-t border-slate-100">
                <div class="text-sm text-slate-500 mb-2">Description</div>
                <p class="text-sm text-slate-700">{{ $product->description }}</p>
            </div>
        @endif
    </div>
</div>

@endsection