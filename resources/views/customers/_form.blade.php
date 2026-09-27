@php($customer = $customer ?? null)

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Name *</label>
        <input type="text" name="name" value="{{ old('name', $customer->name ?? '') }}" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
        @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Company</label>
        <input type="text" name="company" value="{{ old('company', $customer->company ?? '') }}" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
        <input type="email" name="email" value="{{ old('email', $customer->email ?? '') }}" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
        @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Phone</label>
        <input type="text" name="phone" value="{{ old('phone', $customer->phone ?? '') }}" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Tax Number</label>
        <input type="text" name="tax_number" value="{{ old('tax_number', $customer->tax_number ?? '') }}" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Opening Balance</label>
        <input type="number" step="0.01" name="opening_balance" value="{{ old('opening_balance', $customer->opening_balance ?? 0) }}" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Credit Limit</label>
        <input type="number" step="0.01" min="0" name="credit_limit" value="{{ old('credit_limit', $customer->credit_limit ?? 0) }}" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">City</label>
        <input type="text" name="city" value="{{ old('city', $customer->city ?? '') }}" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Country</label>
        <input type="text" name="country" value="{{ old('country', $customer->country ?? '') }}" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-slate-700 mb-1">Address</label>
        <textarea name="address" rows="2" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">{{ old('address', $customer->address ?? '') }}</textarea>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-slate-700 mb-1">Notes</label>
        <textarea name="notes" rows="2" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">{{ old('notes', $customer->notes ?? '') }}</textarea>
    </div>

    <div class="md:col-span-2">
        <label class="inline-flex items-center gap-2 text-sm">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $customer->is_active ?? true)) class="rounded text-blue-600">
            <span>Active</span>
        </label>
    </div>
</div>