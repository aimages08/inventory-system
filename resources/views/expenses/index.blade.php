@extends('layouts.app')

@section('title', 'Expenses')

@section('content')

<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Expenses</h1>
        <p class="text-sm text-slate-500">Business expenses & costs</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('expense-categories.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg text-sm font-medium">
            <i class="bi bi-tags"></i> Categories
        </a>
        <a href="{{ route('expenses.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">
            <i class="bi bi-plus-lg"></i> Add Expense
        </a>
    </div>
</div>

<div class="bg-gradient-to-br from-red-500 to-red-600 text-white rounded-xl shadow-sm p-6 mb-4">
    <div class="text-xs uppercase opacity-80">Total (filtered)</div>
    <div class="text-3xl font-bold">{{ number_format($total, 2) }}</div>
</div>

<form method="GET" class="bg-white rounded-xl shadow-sm p-4 mb-4 grid grid-cols-1 md:grid-cols-5 gap-3">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..."
           class="border border-slate-200 rounded-lg px-3 py-2 text-sm md:col-span-2">
    <select name="category_id" class="border border-slate-200 rounded-lg px-3 py-2 text-sm">
        <option value="">All Categories</option>
        @foreach($categories as $c)
            <option value="{{ $c->id }}" @selected(request('category_id') == $c->id)>{{ $c->name }}</option>
        @endforeach
    </select>
    <input type="date" name="from" value="{{ request('from') }}" class="border border-slate-200 rounded-lg px-3 py-2 text-sm">
    <div class="flex gap-2">
        <button class="flex-1 px-3 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-sm"><i class="bi bi-search"></i></button>
        <a href="{{ route('expenses.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg text-sm">Reset</a>
    </div>
</form>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-600 text-left">
                <tr>
                    <th class="px-4 py-3">Reference</th>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3">Title</th>
                    <th class="px-4 py-3">Category</th>
                    <th class="px-4 py-3">Method</th>
                    <th class="px-4 py-3 text-right">Amount</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($expenses as $expense)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $expense->reference_no }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $expense->expense_date->format('d M Y') }}</td>
                        <td class="px-4 py-3">{{ $expense->title }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $expense->category->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ ucfirst($expense->payment_method) }}</td>
                        <td class="px-4 py-3 text-right font-medium text-red-600">{{ number_format($expense->amount, 2) }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="inline-flex gap-1">
                                <a href="{{ route('expenses.edit', $expense) }}" class="p-2 text-slate-500 hover:text-amber-600 hover:bg-amber-50 rounded"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('expenses.destroy', $expense) }}" method="POST" onsubmit="return confirm('Delete?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button class="p-2 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-12 text-center text-slate-400">
                        <i class="bi bi-receipt text-4xl block mb-2"></i> No expenses yet.
                    </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $expenses->links() }}</div>

@endsection