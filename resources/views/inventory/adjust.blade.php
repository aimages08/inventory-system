@extends('layouts.app')

@section('title', 'Adjust Stock — ' . $product->name)

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Adjust Stock</h1>
        <p class="text-sm text-slate-500">{{ $product->name }} — Current: <strong>{{ $product->stock_quantity }}</strong></p>
    </div>
    <a href="{{ route('inventory.index') }}" class="text-sm text-slate-500 hover:text-slate-700">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<form method="POST" action="{{ route('inventory.adjust', $product) }}" class="bg-white rounded-xl shadow-sm p-6 max-w-2xl">
    @csrf

    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Adjustment Type *</label>
            <select name="type" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
                <option value="in">Stock In (add)</option>
                <option value="out">Stock Out (subtract)</option>
                <option value="adjustment">Set Exact Quantity</option>
            </select>
            <p class="text-xs text-slate-400 mt-1">
                <strong>In</strong> adds, <strong>Out</strong> subtracts, <strong>Adjustment</strong> sets the exact stock level.
            </p>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Quantity *</label>
            <input type="number" name="quantity" min="0" value="{{ old('quantity', $product->stock_quantity) }}" required
                   class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
            @error('quantity')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Reason</label>
            <select name="reason" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
                <option value="">— Select —</option>
                <option value="damaged">Damaged</option>
                <option value="expired">Expired</option>
                <option value="lost">Lost</option>
                <option value="found">Found</option>
                <option value="opening">Opening Stock</option>
                <option value="correction">Correction</option>
                <option value="other">Other</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Notes</label>
            <textarea name="notes" rows="3" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">{{ old('notes') }}</textarea>
        </div>
    </div>

    <div class="flex gap-2 mt-6">
        <button class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">
            <i class="bi bi-check-lg me-1"></i> Apply Adjustment
        </button>
        <a href="{{ route('inventory.index') }}" class="px-6 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg text-sm">Cancel</a>
    </div>
</form>

@endsection