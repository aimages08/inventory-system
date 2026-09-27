@extends('layouts.app')

@section('title', 'Settings')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Settings</h1>
    <p class="text-sm text-slate-500">Configure your business preferences</p>
</div>

<form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data"
      x-data="{ tab: 'business' }" class="bg-white rounded-xl shadow-sm">
    @csrf @method('PUT')

    {{-- Tabs --}}
    <div class="border-b border-slate-200 flex overflow-x-auto">
        @php
            $tabs = [
                'business'      => ['Business', 'bi-building'],
                'currency'      => ['Currency', 'bi-currency-dollar'],
                'tax'           => ['Tax', 'bi-percent'],
                'invoice'       => ['Invoice', 'bi-receipt'],
                'datetime'      => ['Date & Time', 'bi-calendar3'],
                'notifications' => ['Notifications', 'bi-bell'],
            ];
        @endphp
        @foreach($tabs as $key => $meta)
            <button type="button" @click="tab = '{{ $key }}'"
                    :class="tab === '{{ $key }}' ? 'border-blue-500 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700'"
                    class="px-5 py-3 text-sm font-medium border-b-2 whitespace-nowrap transition">
                <i class="bi {{ $meta[1] }} me-1"></i> {{ $meta[0] }}
            </button>
        @endforeach
    </div>

    <div class="p-6">

        {{-- ============ BUSINESS TAB ============ --}}
        <div x-show="tab === 'business'" class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">Business Name</label>
                <input type="text" name="business_name" value="{{ $settings['business_name'] ?? '' }}"
                       class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                <input type="email" name="business_email" value="{{ $settings['business_email'] ?? '' }}"
                       class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Phone</label>
                <input type="text" name="business_phone" value="{{ $settings['business_phone'] ?? '' }}"
                       class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tax Number</label>
                <input type="text" name="business_tax_number" value="{{ $settings['business_tax_number'] ?? '' }}"
                       class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Logo</label>
                <input type="file" name="logo" accept="image/*"
                       class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
                @if(!empty($settings['logo']))
                    <img src="{{ asset('storage/' . $settings['logo']) }}" class="mt-2 h-16 rounded">
                @endif
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">Address</label>
                <textarea name="business_address" rows="3"
                          class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">{{ $settings['business_address'] ?? '' }}</textarea>
            </div>
        </div>

        {{-- ============ CURRENCY TAB ============ --}}
        <div x-show="tab === 'currency'" class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Currency Code</label>
                <input type="text" name="currency_code" value="{{ $settings['currency_code'] ?? 'USD' }}"
                       placeholder="USD, PKR, EUR..."
                       class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Symbol</label>
                <input type="text" name="currency_symbol" value="{{ $settings['currency_symbol'] ?? '$' }}"
                       placeholder="$, Rs, €..."
                       class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Position</label>
                <select name="currency_position" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
                    <option value="before" @selected(($settings['currency_position'] ?? 'before') === 'before')>Before amount ($100)</option>
                    <option value="after" @selected(($settings['currency_position'] ?? '') === 'after')>After amount (100 $)</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Decimal Places</label>
                <input type="number" min="0" max="4" name="decimal_places"
                       value="{{ $settings['decimal_places'] ?? 2 }}"
                       class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Thousand Separator</label>
                <input type="text" name="thousand_separator" maxlength="5"
                       value="{{ $settings['thousand_separator'] ?? ',' }}"
                       placeholder=", or ."
                       class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
            </div>
        </div>

        {{-- ============ TAX TAB ============ --}}
        <div x-show="tab === 'tax'" class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Default Tax Rate (%)</label>
                <input type="number" step="0.01" min="0" max="100" name="default_tax_rate"
                       value="{{ $settings['default_tax_rate'] ?? 0 }}"
                       class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
            </div>

            <div class="flex items-end">
                <label class="inline-flex items-center gap-2 text-sm">
                    <input type="hidden" name="tax_inclusive" value="0">
                    <input type="checkbox" name="tax_inclusive" value="1"
                           @checked(($settings['tax_inclusive'] ?? '0') === '1')
                           class="rounded text-blue-600">
                    <span>Tax Inclusive Pricing</span>
                </label>
            </div>
        </div>

        {{-- ============ INVOICE TAB ============ --}}
        <div x-show="tab === 'invoice'" class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Invoice Prefix</label>
                <input type="text" name="invoice_prefix" value="{{ $settings['invoice_prefix'] ?? 'INV-' }}"
                       class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">Invoice Footer</label>
                <textarea name="invoice_footer" rows="2"
                          class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">{{ $settings['invoice_footer'] ?? 'Thank you for your business!' }}</textarea>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">Terms & Conditions</label>
                <textarea name="invoice_terms" rows="4"
                          class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">{{ $settings['invoice_terms'] ?? '' }}</textarea>
            </div>
        </div>

        {{-- ============ DATE & TIME TAB ============ --}}
        <div x-show="tab === 'datetime'" class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Date Format</label>
                <select name="date_format" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
                    @foreach(['d/m/Y', 'm/d/Y', 'd-m-Y', 'Y-m-d', 'd M Y'] as $fmt)
                        <option value="{{ $fmt }}" @selected(($settings['date_format'] ?? 'd/m/Y') === $fmt)>{{ $fmt }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Timezone</label>
                <input type="text" name="timezone" value="{{ $settings['timezone'] ?? 'Asia/Karachi' }}"
                       class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
            </div>
        </div>

        {{-- ============ NOTIFICATIONS TAB ============ --}}
        <div x-show="tab === 'notifications'" class="space-y-4">
            <label class="flex items-center gap-3 text-sm">
                <input type="hidden" name="notify_low_stock" value="0">
                <input type="checkbox" name="notify_low_stock" value="1"
                       @checked(($settings['notify_low_stock'] ?? '1') === '1')
                       class="rounded text-blue-600">
                <span class="text-slate-700">Notify when a product is low on stock</span>
            </label>

            <label class="flex items-center gap-3 text-sm">
                <input type="hidden" name="notify_out_of_stock" value="0">
                <input type="checkbox" name="notify_out_of_stock" value="1"
                       @checked(($settings['notify_out_of_stock'] ?? '1') === '1')
                       class="rounded text-blue-600">
                <span class="text-slate-700">Notify when a product is out of stock</span>
            </label>

            <label class="flex items-center gap-3 text-sm">
                <input type="hidden" name="notify_payment_due" value="0">
                <input type="checkbox" name="notify_payment_due" value="1"
                       @checked(($settings['notify_payment_due'] ?? '1') === '1')
                       class="rounded text-blue-600">
                <span class="text-slate-700">Notify when a payment is due</span>
            </label>
        </div>
    </div>

    {{-- Save button --}}
    <div class="border-t border-slate-200 px-6 py-4 flex gap-2">
        <button class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">
            <i class="bi bi-check-lg me-1"></i> Save Settings
        </button>
        <a href="{{ route('dashboard') }}" class="px-6 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg text-sm">Cancel</a>
    </div>
</form>

@endsection