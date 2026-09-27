@php($supplier = $supplier ?? null)

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Name *</label>
        <input type="text" name="name" value="{{ old('name', $supplier->name ?? '') }}" required
               class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
        @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Company</label>
        <input type="text" name="company" value="{{ old('company', $supplier->company ?? '') }}"
               class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
        <input type="email" name="email" value="{{ old('email', $supplier->email ?? '') }}"
               class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
        @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Phone</label>
        <input type="text" name="phone" value="{{ old('phone', $supplier->phone ?? '') }}"
               class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Tax Number</label>
        <input type="text" name="tax_number" value="{{ old('tax_number', $supplier->tax_number ?? '') }}"
               class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Opening Balance</label>
        <input type="number" step="0.01" name="opening_balance" value="{{ old('opening_balance', $supplier->opening_balance ?? 0) }}"
               class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">City</label>
        <input type="text" name="city" value="{{ old('city', $supplier->city ?? '') }}"
               class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Country</label>
        <input type="text" name="country" value="{{ old('country', $supplier->country ?? '') }}"
               class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-slate-700 mb-1">Address</label>
        <textarea name="address" rows="2" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">{{ old('address', $supplier->address ?? '') }}</textarea>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-slate-700 mb-1">Notes</label>
        <textarea name="notes" rows="2" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">{{ old('notes', $supplier->notes ?? '') }}</textarea>
    </div>

    <div class="md:col-span-2">
        <label class="inline-flex items-center gap-2 text-sm">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $supplier->is_active ?? true)) class="rounded text-blue-600">
            <span>Active</span>
        </label>
    </div>

</div>