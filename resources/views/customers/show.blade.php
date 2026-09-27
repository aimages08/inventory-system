@extends('layouts.app')

@section('title', $customer->name)

@section('content')

<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">{{ $customer->name }}</h1>
        <p class="text-sm text-slate-500">{{ $customer->company ?: 'Customer' }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('customers.edit', $customer) }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-sm"><i class="bi bi-pencil me-1"></i> Edit</a>
        <a href="{{ route('customers.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg text-sm">Back</a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-semibold text-slate-800 mb-4">Contact Info</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div><span class="text-slate-500">Email:</span> <span class="font-medium">{{ $customer->email ?: '—' }}</span></div>
            <div><span class="text-slate-500">Phone:</span> <span class="font-medium">{{ $customer->phone ?: '—' }}</span></div>
            <div><span class="text-slate-500">Tax Number:</span> <span class="font-medium">{{ $customer->tax_number ?: '—' }}</span></div>
            <div><span class="text-slate-500">City:</span> <span class="font-medium">{{ $customer->city ?: '—' }}</span></div>
            <div><span class="text-slate-500">Country:</span> <span class="font-medium">{{ $customer->country ?: '—' }}</span></div>
            <div><span class="text-slate-500">Status:</span>
                <span class="font-medium {{ $customer->is_active ? 'text-green-600' : 'text-red-600' }}">{{ $customer->is_active ? 'Active' : 'Inactive' }}</span>
            </div>
            <div class="md:col-span-2"><span class="text-slate-500">Address:</span> <span class="font-medium">{{ $customer->address ?: '—' }}</span></div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-semibold text-slate-800 mb-4">Balance</h3>
        <div class="space-y-3 text-sm">
            <div class="flex justify-between"><span class="text-slate-500">Opening</span><span class="font-medium">{{ number_format($customer->opening_balance, 2) }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Credit Limit</span><span class="font-medium">{{ number_format($customer->credit_limit, 2) }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Receivable</span>
                <span class="font-bold text-lg {{ $customer->current_balance > 0 ? 'text-red-600' : 'text-emerald-600' }}">{{ number_format($customer->current_balance, 2) }}</span>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-4">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-semibold text-slate-800 mb-3">Sales History</h3>
        <div class="text-center text-slate-400 py-10 border-2 border-dashed border-slate-200 rounded-lg">
            <i class="bi bi-cart-check text-3xl block mb-2"></i> No sales yet
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-semibold text-slate-800 mb-3">Payment History</h3>
        <div class="text-center text-slate-400 py-10 border-2 border-dashed border-slate-200 rounded-lg">
            <i class="bi bi-cash-coin text-3xl block mb-2"></i> No payments yet
        </div>
    </div>
</div>

@endsection