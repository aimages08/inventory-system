@extends('layouts.app')

@section('title', 'Reports')

@section('content')

<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Reports</h1>
        <p class="text-sm text-slate-500">Business performance overview</p>
    </div>
</div>

<form method="GET" class="bg-white rounded-xl shadow-sm p-4 mb-6 grid grid-cols-1 md:grid-cols-4 gap-3">
    <div>
        <label class="block text-xs text-slate-500 mb-1">From</label>
        <input type="date" name="from" value="{{ $from }}" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
    </div>
    <div>
        <label class="block text-xs text-slate-500 mb-1">To</label>
        <input type="date" name="to" value="{{ $to }}" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
    </div>
    <div class="flex items-end">
        <button class="w-full px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-sm"><i class="bi bi-search me-1"></i> Generate</button>
    </div>
</form>

{{-- Financial Summary --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-5">
        <div class="text-xs text-slate-500 uppercase">Sales</div>
        <div class="text-2xl font-bold text-emerald-600">{{ money($salesTotal) }}</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5">
        <div class="text-xs text-slate-500 uppercase">Purchases</div>
        <div class="text-2xl font-bold text-blue-600">{{ money($purchasesTotal) }}</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5">
        <div class="text-xs text-slate-500 uppercase">Expenses</div>
        <div class="text-2xl font-bold text-red-600">{{ money($expensesTotal) }}</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5">
        <div class="text-xs text-slate-500 uppercase">Profit</div>
        <div class="text-2xl font-bold {{ $profit >= 0 ? 'text-emerald-600' : 'text-red-600' }}">{{ money($profit) }}</div>
    </div>
</div>

{{-- Stock Summary --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-5">
        <div class="text-xs text-slate-500 uppercase">Stock Value (cost)</div>
        <div class="text-2xl font-bold">{{ money($stockValue) }}</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5">
        <div class="text-xs text-slate-500 uppercase">Stock Value (retail)</div>
        <div class="text-2xl font-bold">{{ money($retailValue) }}</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5">
        <div class="text-xs text-slate-500 uppercase">Low Stock</div>
        <div class="text-2xl font-bold text-amber-600">{{ $lowStock }}</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5">
        <div class="text-xs text-slate-500 uppercase">Out of Stock</div>
        <div class="text-2xl font-bold text-red-600">{{ $outOfStock }}</div>
    </div>
</div>

{{-- Top Lists --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <div class="bg-white rounded-xl shadow-sm p-5">
        <h3 class="font-semibold text-slate-800 mb-3">Top Products</h3>
        @forelse($topProducts as $p)
            <div class="flex justify-between text-sm border-b border-slate-100 py-2">
                <span class="text-slate-700">{{ $p->name }}</span>
                <span class="font-medium">{{ $p->qty }} sold</span>
            </div>
        @empty
            <p class="text-slate-400 text-sm">No sales yet</p>
        @endforelse
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5">
        <h3 class="font-semibold text-slate-800 mb-3">Top Customers</h3>
        @forelse($topCustomers as $c)
            <div class="flex justify-between text-sm border-b border-slate-100 py-2">
                <span class="text-slate-700">{{ $c->customer->name ?? '—' }}</span>
                <span class="font-medium">{{ money($c->total) }}</span>
            </div>
        @empty
            <p class="text-slate-400 text-sm">No customers yet</p>
        @endforelse
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5">
        <h3 class="font-semibold text-slate-800 mb-3">Top Suppliers</h3>
        @forelse($topSuppliers as $s)
            <div class="flex justify-between text-sm border-b border-slate-100 py-2">
                <span class="text-slate-700">{{ $s->supplier->name ?? '—' }}</span>
                <span class="font-medium">{{ money($s->total) }}</span>
            </div>
        @empty
            <p class="text-slate-400 text-sm">No purchases yet</p>
        @endforelse
    </div>
</div>

@endsection