@extends('layouts.app')

@section('title', 'Payments')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Payments</h1>
    <p class="text-sm text-slate-500">All incoming and outgoing payments</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 text-white rounded-xl shadow-sm p-5">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-xs uppercase opacity-80">Total Received</div>
                <div class="text-2xl font-bold">{{ number_format($totals['received'], 2) }}</div>
            </div>
            <i class="bi bi-arrow-down-circle text-4xl opacity-30"></i>
        </div>
    </div>
    <div class="bg-gradient-to-br from-red-500 to-red-600 text-white rounded-xl shadow-sm p-5">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-xs uppercase opacity-80">Total Paid</div>
                <div class="text-2xl font-bold">{{ number_format($totals['made'], 2) }}</div>
            </div>
            <i class="bi bi-arrow-up-circle text-4xl opacity-30"></i>
        </div>
    </div>
</div>

<div class="flex gap-2 mb-4">
    <a href="{{ route('payments.index', ['type' => 'all']) }}" class="px-4 py-2 rounded-lg text-sm {{ $type === 'all' ? 'bg-slate-800 text-white' : 'bg-white text-slate-700' }}">All</a>
    <a href="{{ route('payments.index', ['type' => 'received']) }}" class="px-4 py-2 rounded-lg text-sm {{ $type === 'received' ? 'bg-emerald-600 text-white' : 'bg-white text-slate-700' }}">Received</a>
    <a href="{{ route('payments.index', ['type' => 'made']) }}" class="px-4 py-2 rounded-lg text-sm {{ $type === 'made' ? 'bg-red-600 text-white' : 'bg-white text-slate-700' }}">Paid</a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-600 text-left">
            <tr>
                <th class="px-4 py-3">Date</th>
                <th class="px-4 py-3">Type</th>
                <th class="px-4 py-3">Party</th>
                <th class="px-4 py-3">Reference</th>
                <th class="px-4 py-3">Method</th>
                <th class="px-4 py-3 text-right">Amount</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($timeline as $row)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 text-slate-500">{{ \Carbon\Carbon::parse($row['date'])->format('d M Y') }}</td>
                    <td class="px-4 py-3">
                        @if($row['type'] === 'received')
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">Received</span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">Paid</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 font-medium text-slate-800">{{ $row['party'] }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $row['reference'] }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ ucfirst($row['method']) }}</td>
                    <td class="px-4 py-3 text-right font-medium {{ $row['type'] === 'received' ? 'text-emerald-600' : 'text-red-600' }}">
                        {{ $row['type'] === 'received' ? '+' : '-' }}{{ number_format($row['amount'], 2) }}
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-12 text-center text-slate-400">
                    <i class="bi bi-cash-coin text-4xl block mb-2"></i> No payments yet.
                </td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection