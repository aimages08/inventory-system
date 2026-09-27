@extends('layouts.app')

@section('title', 'Suppliers')

@section('content')

<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Suppliers</h1>
        <p class="text-sm text-slate-500">Manage your suppliers & vendors</p>
    </div>
    <a href="{{ route('suppliers.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">
        <i class="bi bi-plus-lg"></i> Add Supplier
    </a>
</div>

<form method="GET" class="bg-white rounded-xl shadow-sm p-4 mb-4 grid grid-cols-1 md:grid-cols-3 gap-3">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, company, email..."
           class="border border-slate-200 rounded-lg px-3 py-2 text-sm md:col-span-2">
    <div class="flex gap-2">
        <select name="status" class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm">
            <option value="">All Status</option>
            <option value="active" @selected(request('status') === 'active')>Active</option>
            <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
        </select>
        <button class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-sm"><i class="bi bi-search"></i></button>
        <a href="{{ route('suppliers.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg text-sm">Reset</a>
    </div>
</form>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-600 text-left">
                <tr>
                    <th class="px-4 py-3">Supplier</th>
                    <th class="px-4 py-3">Contact</th>
                    <th class="px-4 py-3">City</th>
                    <th class="px-4 py-3">Balance</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($suppliers as $supplier)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3">
                            <div class="font-medium text-slate-800">{{ $supplier->name }}</div>
                            <div class="text-xs text-slate-400">{{ $supplier->company ?: '—' }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-slate-700">{{ $supplier->phone ?: '—' }}</div>
                            <div class="text-xs text-slate-400">{{ $supplier->email ?: '—' }}</div>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $supplier->city ?: '—' }}</td>
                        <td class="px-4 py-3 font-medium {{ $supplier->current_balance > 0 ? 'text-red-600' : 'text-slate-800' }}">
                            {{ number_format($supplier->current_balance, 2) }}
                        </td>
                        <td class="px-4 py-3">
                            @if($supplier->is_active)
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">Active</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-slate-100 text-slate-600">Inactive</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="inline-flex items-center gap-1">
                                <a href="{{ route('suppliers.show', $supplier) }}" class="p-2 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('suppliers.edit', $supplier) }}" class="p-2 text-slate-500 hover:text-amber-600 hover:bg-amber-50 rounded"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" onsubmit="return confirm('Delete this supplier?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button class="p-2 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-slate-400">
                            <i class="bi bi-truck text-4xl block mb-2"></i>
                            No suppliers yet. Click <strong>Add Supplier</strong> to create one.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $suppliers->links() }}</div>

@endsection