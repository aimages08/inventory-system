@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Welcome back, {{ auth()->user()->name }} 👋</h1>
    <p class="text-sm text-slate-500">Here's what's happening with your inventory today.</p>
</div>

{{-- Row 1: Core Stats --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-lg bg-blue-500 text-white flex items-center justify-center text-xl"><i class="bi bi-box"></i></div>
        <div>
            <div class="text-xs text-slate-500">Total Products</div>
            <div class="text-2xl font-bold text-slate-800">{{ number_format($stats['total_products']) }}</div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-lg bg-emerald-500 text-white flex items-center justify-center text-xl"><i class="bi bi-currency-dollar"></i></div>
        <div>
            <div class="text-xs text-slate-500">Stock Value</div>
            <div class="text-2xl font-bold text-slate-800">{{ number_format($stats['total_stock_value'], 2) }}</div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-lg bg-amber-500 text-white flex items-center justify-center text-xl"><i class="bi bi-exclamation-triangle"></i></div>
        <div>
            <div class="text-xs text-slate-500">Low Stock</div>
            <div class="text-2xl font-bold text-slate-800">{{ $stats['low_stock_count'] }}</div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-lg bg-red-500 text-white flex items-center justify-center text-xl"><i class="bi bi-x-octagon"></i></div>
        <div>
            <div class="text-xs text-slate-500">Out of Stock</div>
            <div class="text-2xl font-bold text-slate-800">{{ $stats['out_of_stock_count'] }}</div>
        </div>
    </div>
</div>

{{-- Row 2: Today + Dues --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-5 flex items-center justify-between">
        <div>
            <div class="text-xs text-slate-500">Today's Sales</div>
            <div class="text-xl font-bold text-emerald-600">{{ number_format($stats['today_sales'], 2) }}</div>
        </div>
        <i class="bi bi-cart-check text-3xl text-emerald-500/30"></i>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5 flex items-center justify-between">
        <div>
            <div class="text-xs text-slate-500">Today's Purchases</div>
            <div class="text-xl font-bold text-blue-600">{{ number_format($stats['today_purchases'], 2) }}</div>
        </div>
        <i class="bi bi-cart-plus text-3xl text-blue-500/30"></i>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5 flex items-center justify-between">
        <div>
            <div class="text-xs text-slate-500">Customer Receivables</div>
            <div class="text-xl font-bold text-amber-600">{{ number_format($stats['customer_dues'], 2) }}</div>
        </div>
        <i class="bi bi-people text-3xl text-amber-500/30"></i>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5 flex items-center justify-between">
        <div>
            <div class="text-xs text-slate-500">Supplier Payables</div>
            <div class="text-xl font-bold text-red-600">{{ number_format($stats['supplier_dues'], 2) }}</div>
        </div>
        <i class="bi bi-truck text-3xl text-red-500/30"></i>
    </div>
</div>

{{-- Row 3: Chart + Recent Sales --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-5">
        <h3 class="font-semibold text-slate-800 mb-4"><i class="bi bi-graph-up me-2"></i>Sales vs Purchases (Last 7 Days)</h3>
        <canvas id="salesChart" height="90"></canvas>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5">
        <h3 class="font-semibold text-slate-800 mb-4"><i class="bi bi-clock-history me-2"></i>Recent Sales</h3>
        @forelse($recentSales as $sale)
            <div class="flex justify-between text-sm border-b border-slate-100 py-2">
                <div>
                    <div class="font-medium text-slate-800">{{ $sale->reference_no }}</div>
                    <div class="text-xs text-slate-400">{{ $sale->customer->name ?? '—' }}</div>
                </div>
                <div class="text-emerald-600 font-medium">{{ number_format($sale->total, 2) }}</div>
            </div>
        @empty
            <div class="text-center text-slate-400 py-8">
                <i class="bi bi-inbox text-3xl block mb-2"></i> No sales yet
            </div>
        @endforelse
    </div>
</div>

{{-- Row 4: Low Stock Alert --}}
<div class="bg-white rounded-xl shadow-sm p-5">
    <h3 class="font-semibold text-slate-800 mb-4"><i class="bi bi-exclamation-triangle text-amber-500 me-2"></i>Low Stock Alert</h3>
    @if($lowStockProducts->count())
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-slate-500 text-left">
                    <tr>
                        <th class="py-2">Product</th>
                        <th class="py-2">SKU</th>
                        <th class="py-2 text-right">Stock</th>
                        <th class="py-2 text-right">Min</th>
                        <th class="py-2 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($lowStockProducts as $p)
                        <tr>
                            <td class="py-2 font-medium">{{ $p->name }}</td>
                            <td class="py-2 text-slate-500">{{ $p->sku }}</td>
                            <td class="py-2 text-right text-amber-600 font-bold">{{ $p->stock_quantity }}</td>
                            <td class="py-2 text-right text-slate-500">{{ $p->minimum_stock }}</td>
                            <td class="py-2 text-right">
                                <a href="{{ route('inventory.adjust.form', $p) }}" class="text-blue-600 hover:underline text-xs">Restock</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center text-slate-400 py-8">
            <i class="bi bi-check-circle text-3xl block mb-2 text-emerald-400"></i>
            All products are well-stocked
        </div>
    @endif
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const ctx = document.getElementById('salesChart');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($chart['labels']),
        datasets: [
            {
                label: 'Sales',
                data: @json($chart['sales']),
                borderColor: '#10b981',
                backgroundColor: 'rgba(16,185,129,0.1)',
                tension: 0.4,
                fill: true,
            },
            {
                label: 'Purchases',
                data: @json($chart['purchases']),
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59,130,246,0.1)',
                tension: 0.4,
                fill: true,
            },
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } },
        scales: { y: { beginAtZero: true } }
    }
});
</script>
@endpush