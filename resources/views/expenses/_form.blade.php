@php($expense = $expense ?? null)

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-slate-700 mb-1">Title *</label>
        <input type="text" name="title" value="{{ old('title', $expense->title ?? '') }}" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
        @error('title')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Category</label>
        <select name="expense_category_id" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
            <option value="">— None —</option>
            @foreach($categories as $c)
                <option value="{{ $c->id }}" @selected(old('expense_category_id', $expense->expense_category_id ?? '') == $c->id)>{{ $c->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Amount *</label>
        <input type="number" step="0.01" min="0.01" name="amount" value="{{ old('amount', $expense->amount ?? '') }}" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
        @error('amount')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Date *</label>
        <input type="date" name="expense_date" value="{{ old('expense_date', isset($expense) ? $expense->expense_date->toDateString() : now()->toDateString()) }}" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Payment Method *</label>
        <select name="payment_method" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
            @foreach(['cash','bank','card','online'] as $m)
                <option value="{{ $m }}" @selected(old('payment_method', $expense->payment_method ?? 'cash') === $m)>{{ ucfirst($m) }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Reference</label>
        <input type="text" name="reference" value="{{ old('reference', $expense->reference ?? '') }}" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-slate-700 mb-1">Notes</label>
        <textarea name="notes" rows="2" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">{{ old('notes', $expense->notes ?? '') }}</textarea>
    </div>
</div>