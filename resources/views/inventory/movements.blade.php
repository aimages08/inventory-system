@extends('layouts.app')

@section('title', 'Stock Movements')

@section('content')

<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Stock Movements</h1>
        <p class="text-sm text-slate-500">Every stock in/out/adjustment</p>
    </div>
    <a href="{{ route('inventory.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg text-sm font-medium">
        <i class="bi bi-arrow-left"></i> Current Stock
    </a>
</div>

<form method="GET" class="bg-white rounded-xl shadow-sm p-4 mb-4 grid grid-cols-1 md:grid-cols-5 gap-3">
    <select name="product_id" class="border border-slate-200 rounded-lg px-3 py-2 text-sm md:col-span-2">
        <option value="">All Products</option>
        @foreach($products as $p)
            <option value="{{ $p->id }}" @selected(request('product_id') == $p->id)>{{ $p->name }}</option>
        @endforeach
    </select>
    <select name="type" class="border border-slate-200 rounded-lg px-3 py-2 text-sm">
        <option value="">All Types</option>
        <option value="in"  @selected(request('type') === 'in')>In</option>
        <option value="out" @selected(request('type') === 'out')>Out</option>
        <option value="adjustment" @selected(request('type') === 'adjustment')>Adjustment</option>
    </select>
    <input type="date" name="from" value="{{ request('from') }}" class="border border-slate-200 rounded-lg px-3 py-2 text-sm">
    <div class="flex gap-2">
        <button class="flex-1 px-3 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-sm"><i class="bi bi-search"></i></button>
        <a href="{{ route('inventory.movements') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg text-sm">Reset</a>
    </div>
</form>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-600 text-left">
                <tr>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3">Product</th>
                    <th class="px-4 py-3">Type</th>
                    <th class="px-4 py-3 text-right">Qty</th>
                    <th class="px-4 py-3 text-right">Before</th>
                    <th class="px-4 py-3 text-right">After</th>
                    <th class="px-4 py-3">Reason</th>
                    <th class="px-4 py-3">By</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($movements as $m)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-slate-500 text-xs">{{ $m->created_at->format('d M Y H:i') }}</td>
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $m->product->name ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @if($m->type === 'in')
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">In</span>
                            @elseif($m->type === 'out')
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">Out</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-amber-100 text-amber-700">Adjust</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right font-medium">{{ $m->quantity }}</td>
                        <td class="px-4 py-3 text-right text-slate-500">{{ $m->stock_before }}</td>
                        <td class="px-4 py-3 text-right font-medium">{{ $m->stock_after }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $m->reason ?? '—' }}</td>
                        <td class="px-4 py-3 text-slate-500 text-xs">{{ $m->user->name ?? 'system' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-4 py-12 text-center text-slate-400">
                        <i class="bi bi-clock-history text-4xl block mb-2"></i> No stock movements yet.
                    </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $movements->links() }}</div>

@endsection