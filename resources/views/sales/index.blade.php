@extends('layouts.app')

@section('title', 'Sales')

@section('content')

<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Sales</h1>
        <p class="text-sm text-slate-500">All sales invoices</p>
    </div>
    <a href="{{ route('sales.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">
        <i class="bi bi-plus-lg"></i> New Sale
    </a>
</div>

<form method="GET" class="bg-white rounded-xl shadow-sm p-4 mb-4 grid grid-cols-1 md:grid-cols-5 gap-3">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Reference or customer..."
           class="border border-slate-200 rounded-lg px-3 py-2 text-sm md:col-span-2">
    <select name="customer_id" class="border border-slate-200 rounded-lg px-3 py-2 text-sm">
        <option value="">All Customers</option>
        @foreach($customers as $c)
            <option value="{{ $c->id }}" @selected(request('customer_id') == $c->id)>{{ $c->name }}</option>
        @endforeach
    </select>
    <input type="date" name="from" value="{{ request('from') }}" class="border border-slate-200 rounded-lg px-3 py-2 text-sm">
    <div class="flex gap-2">
        <button class="flex-1 px-3 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-sm"><i class="bi bi-search"></i></button>
        <a href="{{ route('sales.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg text-sm">Reset</a>
    </div>
</form>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-600 text-left">
                <tr>
                    <th class="px-4 py-3">Reference</th>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3">Customer</th>
                    <th class="px-4 py-3 text-right">Total</th>
                    <th class="px-4 py-3 text-right">Paid</th>
                    <th class="px-4 py-3 text-right">Due</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($sales as $sale)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $sale->reference_no }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $sale->sale_date->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-slate-700">{{ $sale->customer->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-right font-medium">{{ number_format($sale->total, 2) }}</td>
                        <td class="px-4 py-3 text-right text-emerald-600">{{ number_format($sale->paid, 2) }}</td>
                        <td class="px-4 py-3 text-right font-medium {{ $sale->due > 0 ? 'text-red-600' : 'text-slate-800' }}">
                            {{ number_format($sale->due, 2) }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="inline-flex gap-1">
                                <a href="{{ route('sales.show', $sale) }}" class="p-2 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded"><i class="bi bi-eye"></i></a>
                                <form action="{{ route('sales.destroy', $sale) }}" method="POST" onsubmit="return confirm('Delete this sale? Stock will be restored.')" class="inline">
                                    @csrf @method('DELETE')
                                    <button class="p-2 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-12 text-center text-slate-400">
                        <i class="bi bi-cart-check text-4xl block mb-2"></i>
                        No sales yet. Click <strong>New Sale</strong> to create one.
                    </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $sales->links() }}</div>

@endsection