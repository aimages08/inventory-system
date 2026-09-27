@extends('layouts.app')

@section('title', 'Purchases')

@section('content')

<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Purchases</h1>
        <p class="text-sm text-slate-500">Purchase orders from suppliers</p>
    </div>
    <a href="{{ route('purchases.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">
        <i class="bi bi-plus-lg"></i> New Purchase
    </a>
</div>

<form method="GET" class="bg-white rounded-xl shadow-sm p-4 mb-4 grid grid-cols-1 md:grid-cols-5 gap-3">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Reference or supplier..."
           class="border border-slate-200 rounded-lg px-3 py-2 text-sm md:col-span-2">
    <select name="supplier_id" class="border border-slate-200 rounded-lg px-3 py-2 text-sm">
        <option value="">All Suppliers</option>
        @foreach($suppliers as $s)
            <option value="{{ $s->id }}" @selected(request('supplier_id') == $s->id)>{{ $s->name }}</option>
        @endforeach
    </select>
    <input type="date" name="from" value="{{ request('from') }}" class="border border-slate-200 rounded-lg px-3 py-2 text-sm">
    <div class="flex gap-2">
        <button class="flex-1 px-3 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-sm"><i class="bi bi-search"></i></button>
        <a href="{{ route('purchases.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg text-sm">Reset</a>
    </div>
</form>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-600 text-left">
                <tr>
                    <th class="px-4 py-3">Reference</th>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3">Supplier</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">Paid</th>
                    <th class="px-4 py-3">Due</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($purchases as $p)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $p->reference_no }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $p->purchase_date->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-slate-700">{{ $p->supplier->name }}</td>
                        <td class="px-4 py-3 font-medium">{{ number_format($p->total, 2) }}</td>
                        <td class="px-4 py-3 text-emerald-600">{{ number_format($p->paid, 2) }}</td>
                        <td class="px-4 py-3 font-medium {{ $p->due > 0 ? 'text-red-600' : 'text-slate-800' }}">
                            {{ number_format($p->due, 2) }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="inline-flex gap-1">
                                <a href="{{ route('purchases.show', $p) }}" class="p-2 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded"><i class="bi bi-eye"></i></a>
                                <form action="{{ route('purchases.destroy', $p) }}" method="POST" onsubmit="return confirm('Delete this purchase? Stock will be reverted.')" class="inline">
                                    @csrf @method('DELETE')
                                    <button class="p-2 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-12 text-center text-slate-400">
                        <i class="bi bi-cart-plus text-4xl block mb-2"></i>
                        No purchases yet. Click <strong>New Purchase</strong> to create one.
                    </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $purchases->links() }}</div>

@endsection