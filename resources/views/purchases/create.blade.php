@extends('layouts.app')

@section('title', 'New Purchase')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-slate-800">New Purchase</h1>
    <a href="{{ route('purchases.index') }}" class="text-sm text-slate-500 hover:text-slate-700">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<form method="POST" action="{{ route('purchases.store') }}" x-data="purchaseForm()" class="space-y-5">
    @csrf

    {{-- Top info --}}
    <div class="bg-white rounded-xl shadow-sm p-5 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Supplier *</label>
            <select name="supplier_id" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
                <option value="">— Select —</option>
                @foreach($suppliers as $s)
                    <option value="{{ $s->id }}" @selected(old('supplier_id') == $s->id)>{{ $s->name }}</option>
                @endforeach
            </select>
            @error('supplier_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Purchase Date *</label>
            <input type="date" name="purchase_date" required value="{{ old('purchase_date', now()->toDateString()) }}"
                   class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
            <input type="text" value="Received" disabled class="w-full border border-slate-100 bg-slate-50 rounded-lg px-3 py-2 text-sm">
        </div>
    </div>

    {{-- Items --}}
    <div class="bg-white rounded-xl shadow-sm p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-slate-800">Items</h3>
            <button type="button" @click="addRow()" class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-xs font-medium">
                <i class="bi bi-plus-lg"></i> Add Item
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-slate-500 text-xs uppercase">
                    <tr>
                        <th class="text-left py-2 w-1/3">Product</th>
                        <th class="text-left py-2">Qty</th>
                        <th class="text-left py-2">Unit Cost</th>
                        <th class="text-left py-2">Discount</th>
                        <th class="text-left py-2">Tax</th>
                        <th class="text-right py-2">Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(row, index) in rows" :key="index">
                        <tr class="border-t border-slate-100">
                            <td class="py-2 pr-2">
                                <select :name="`items[${index}][product_id]`" x-model="row.product_id" @change="setPrice(row)" required
                                        class="w-full border border-slate-200 rounded-lg px-2 py-1.5 text-sm">
                                    <option value="">— Select —</option>
                                    @foreach($products as $p)
                                        <option value="{{ $p->id }}" data-price="{{ $p->purchase_price }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="py-2 pr-2">
                                <input type="number" min="1" x-model.number="row.quantity" @input="calc(row)" :name="`items[${index}][quantity]`" required
                                       class="w-20 border border-slate-200 rounded-lg px-2 py-1.5 text-sm">
                            </td>
                            <td class="py-2 pr-2">
                                <input type="number" step="0.01" min="0" x-model.number="row.unit_cost" @input="calc(row)" :name="`items[${index}][unit_cost]`" required
                                       class="w-24 border border-slate-200 rounded-lg px-2 py-1.5 text-sm">
                            </td>
                            <td class="py-2 pr-2">
                                <input type="number" step="0.01" min="0" x-model.number="row.discount" @input="calc(row)" :name="`items[${index}][discount]`"
                                       class="w-20 border border-slate-200 rounded-lg px-2 py-1.5 text-sm">
                            </td>
                            <td class="py-2 pr-2">
                                <input type="number" step="0.01" min="0" x-model.number="row.tax" @input="calc(row)" :name="`items[${index}][tax]`"
                                       class="w-20 border border-slate-200 rounded-lg px-2 py-1.5 text-sm">
                            </td>
                            <td class="py-2 text-right font-medium" x-text="row.subtotal.toFixed(2)"></td>
                            <td class="py-2 pl-2 text-right">
                                <button type="button" @click="removeRow(index)" class="p-1.5 text-slate-400 hover:text-red-600">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Totals + Payment --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <div class="bg-white rounded-xl shadow-sm p-5 lg:col-span-2">
            <h3 class="font-semibold text-slate-800 mb-4">Notes</h3>
            <textarea name="notes" rows="4" placeholder="Optional purchase notes..."
                      class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">{{ old('notes') }}</textarea>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5 space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-slate-500">Subtotal</span>
                <span class="font-medium" x-text="totals.subtotal.toFixed(2)"></span>
            </div>

            <div class="flex justify-between items-center gap-2">
                <span class="text-slate-500">Order Discount</span>
                <input type="number" step="0.01" min="0" name="discount" x-model.number="orderDiscount" @input="recalc()"
                       class="w-24 border border-slate-200 rounded-lg px-2 py-1 text-right text-sm">
            </div>

            <div class="flex justify-between items-center gap-2">
                <span class="text-slate-500">Shipping</span>
                <input type="number" step="0.01" min="0" name="shipping" x-model.number="orderShipping" @input="recalc()"
                       class="w-24 border border-slate-200 rounded-lg px-2 py-1 text-right text-sm">
            </div>

            <div class="flex justify-between text-slate-500">
                <span>Tax</span>
                <span x-text="totals.tax.toFixed(2)"></span>
            </div>

            <div class="border-t border-slate-100 pt-3 flex justify-between text-base font-bold">
                <span>Total</span>
                <span x-text="totals.grand.toFixed(2)"></span>
            </div>

            <div class="border-t border-slate-100 pt-3 space-y-2">
                <div class="flex justify-between items-center gap-2">
                    <span class="text-slate-500">Paid Now</span>
                    <input type="number" step="0.01" min="0" name="paid_amount" x-model.number="paidAmount"
                           class="w-24 border border-slate-200 rounded-lg px-2 py-1 text-right text-sm">
                </div>
                <div>
                    <select name="payment_method" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
                        <option value="cash">Cash</option>
                        <option value="bank">Bank Transfer</option>
                        <option value="card">Card</option>
                        <option value="online">Online</option>
                    </select>
                </div>
                <div class="flex justify-between text-red-600 font-medium">
                    <span>Due</span>
                    <span x-text="dueAmount.toFixed(2)"></span>
                </div>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex gap-2">
        <button class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">
            <i class="bi bi-check-lg me-1"></i> Save Purchase
        </button>
        <a href="{{ route('purchases.index') }}" class="px-6 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg text-sm">Cancel</a>
    </div>
</form>

<script>
function purchaseForm() {
    return {
        rows: [{ product_id: '', quantity: 1, unit_cost: 0, discount: 0, tax: 0, subtotal: 0 }],
        orderDiscount: 0,
        orderShipping: 0,
        paidAmount: 0,
        totals: { subtotal: 0, tax: 0, grand: 0 },
        get dueAmount() { return Math.max(0, this.totals.grand - this.paidAmount); },

        addRow() {
            this.rows.push({ product_id: '', quantity: 1, unit_cost: 0, discount: 0, tax: 0, subtotal: 0 });
        },
        removeRow(i) {
            if (this.rows.length > 1) this.rows.splice(i, 1);
            this.recalc();
        },
        setPrice(row) {
            const opt = event.target.selectedOptions[0];
            if (opt?.dataset.price) row.unit_cost = parseFloat(opt.dataset.price);
            this.calc(row);
        },
        calc(row) {
            row.subtotal = (row.quantity * row.unit_cost) - row.discount + row.tax;
            this.recalc();
        },
        recalc() {
            this.totals.subtotal = this.rows.reduce((s, r) => s + (r.quantity * r.unit_cost - r.discount), 0);
            this.totals.tax      = this.rows.reduce((s, r) => s + (r.tax || 0), 0);
            this.totals.grand    = this.totals.subtotal + this.totals.tax + (this.orderShipping || 0) - (this.orderDiscount || 0);
        },
    }
}
</script>

@endsection