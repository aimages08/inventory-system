@extends('layouts.app')

@section('title', 'Purchase ' . $purchase->reference_no)

@section('content')

<div class="flex flex-wrap items-center justify-between gap-3 mb-6 print:hidden">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">{{ $purchase->reference_no }}</h1>
        <p class="text-sm text-slate-500">{{ $purchase->purchase_date->format('d M Y') }}</p>
    </div>
    <div class="flex gap-2">
        <button onclick="window.print()" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-sm">
            <i class="bi bi-printer me-1"></i> Print
        </button>
        <a href="{{ route('purchases.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg text-sm">Back</a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm p-6 mb-4">
    <div class="flex flex-wrap justify-between gap-4 mb-6">
        <div>
            <div class="text-xs text-slate-500 uppercase">Supplier</div>
            <div class="font-bold text-lg text-slate-800">{{ $purchase->supplier->name }}</div>
            <div class="text-sm text-slate-500">{{ $purchase->supplier->phone }}</div>
            <div class="text-sm text-slate-500">{{ $purchase->supplier->email }}</div>
        </div>
        <div class="text-right">
            <div class="text-xs text-slate-500 uppercase">Reference</div>
            <div class="font-bold text-slate-800">{{ $purchase->reference_no }}</div>
            <div class="text-sm text-slate-500">{{ $purchase->purchase_date->format('d M Y') }}</div>
        </div>
    </div>

    <table class="w-full text-sm mb-6">
        <thead class="bg-slate-50 text-slate-600 text-left">
            <tr>
                <th class="px-3 py-2">Product</th>
                <th class="px-3 py-2 text-right">Qty</th>
                <th class="px-3 py-2 text-right">Unit Cost</th>
                <th class="px-3 py-2 text-right">Discount</th>
                <th class="px-3 py-2 text-right">Tax</th>
                <th class="px-3 py-2 text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @foreach($purchase->items as $item)
                <tr>
                    <td class="px-3 py-2">{{ $item->product->name }}</td>
                    <td class="px-3 py-2 text-right">{{ $item->quantity }}</td>
                    <td class="px-3 py-2 text-right">{{ number_format($item->unit_cost, 2) }}</td>
                    <td class="px-3 py-2 text-right">{{ number_format($item->discount, 2) }}</td>
                    <td class="px-3 py-2 text-right">{{ number_format($item->tax, 2) }}</td>
                    <td class="px-3 py-2 text-right font-medium">{{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="flex justify-end">
        <div class="w-full max-w-xs text-sm space-y-2">
            <div class="flex justify-between"><span class="text-slate-500">Subtotal</span><span>{{ number_format($purchase->subtotal, 2) }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Tax</span><span>{{ number_format($purchase->tax, 2) }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Shipping</span><span>{{ number_format($purchase->shipping, 2) }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Discount</span><span>-{{ number_format($purchase->discount, 2) }}</span></div>
            <div class="flex justify-between border-t border-slate-200 pt-2 text-base font-bold"><span>Total</span><span>{{ number_format($purchase->total, 2) }}</span></div>
            <div class="flex justify-between text-emerald-600"><span>Paid</span><span>{{ number_format($purchase->paid, 2) }}</span></div>
            <div class="flex justify-between text-red-600 font-bold"><span>Due</span><span>{{ number_format($purchase->due, 2) }}</span></div>
        </div>
    </div>
</div>

{{-- Payment section --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 print:hidden">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-semibold text-slate-800 mb-3">Add Payment</h3>
        @if($purchase->due > 0)
            <form method="POST" action="{{ route('purchases.addPayment', $purchase) }}" class="space-y-3">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <input type="number" step="0.01" min="0.01" max="{{ $purchase->due }}" name="amount"
                           value="{{ $purchase->due }}" required
                           class="border border-slate-200 rounded-lg px-3 py-2 text-sm">
                    <select name="method" class="border border-slate-200 rounded-lg px-3 py-2 text-sm">
                        <option value="cash">Cash</option>
                        <option value="bank">Bank</option>
                        <option value="card">Card</option>
                        <option value="online">Online</option>
                    </select>
                </div>
                <input type="date" name="payment_date" value="{{ now()->toDateString() }}" required
                       class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
                <input type="text" name="reference" placeholder="Reference (optional)"
                       class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
                <button class="w-full px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium">
                    <i class="bi bi-cash-coin me-1"></i> Record Payment
                </button>
            </form>
        @else
            <div class="text-center py-8 text-emerald-600">
                <i class="bi bi-check-circle text-4xl block mb-2"></i>
                Fully paid
            </div>
        @endif
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-semibold text-slate-800 mb-3">Payment History</h3>
        @if($purchase->payments->count())
            <div class="space-y-2 text-sm">
                @foreach($purchase->payments as $pmt)
                    <div class="flex justify-between border-b border-slate-100 pb-2">
                        <div>
                            <div class="font-medium">{{ number_format($pmt->amount, 2) }}</div>
                            <div class="text-xs text-slate-400">{{ $pmt->method }} • {{ $pmt->payment_date->format('d M Y') }}</div>
                        </div>
                        <div class="text-xs text-slate-400">{{ $pmt->reference }}</div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-slate-400">
                <i class="bi bi-inbox text-3xl block mb-2"></i> No payments yet
            </div>
        @endif
    </div>
</div>

@endsection