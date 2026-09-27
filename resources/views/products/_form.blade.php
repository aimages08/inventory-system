@php($product = $product ?? null)

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-slate-700 mb-1">Product Name *</label>
        <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required
               class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
        @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">SKU *</label>
        <input type="text" name="sku" value="{{ old('sku', $product->sku ?? '') }}" required
               class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
        @error('sku')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Barcode</label>
        <input type="text" name="barcode" value="{{ old('barcode', $product->barcode ?? '') }}"
               class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
        @error('barcode')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Category</label>
        <select name="category_id" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
            <option value="">— None —</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id ?? '') == $cat->id)>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Brand</label>
        <select name="brand_id" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
            <option value="">— None —</option>
            @foreach($brands as $brand)
                <option value="{{ $brand->id }}" @selected(old('brand_id', $product->brand_id ?? '') == $brand->id)>{{ $brand->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Unit</label>
        <select name="unit_id" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
            <option value="">— None —</option>
            @foreach($units as $unit)
                <option value="{{ $unit->id }}" @selected(old('unit_id', $product->unit_id ?? '') == $unit->id)>{{ $unit->name }} ({{ $unit->short_name }})</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Image</label>
        <input type="file" name="image" accept="image/*"
               class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
        @if($product && $product->image)
            <img src="{{ asset('storage/'.$product->image) }}" class="mt-2 w-16 h-16 rounded object-cover">
        @endif
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Purchase Price *</label>
        <input type="number" step="0.01" min="0" name="purchase_price" value="{{ old('purchase_price', $product->purchase_price ?? 0) }}" required
               class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Selling Price *</label>
        <input type="number" step="0.01" min="0" name="selling_price" value="{{ old('selling_price', $product->selling_price ?? 0) }}" required
               class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Tax Rate (%)</label>
        <input type="number" step="0.01" min="0" max="100" name="tax_rate" value="{{ old('tax_rate', $product->tax_rate ?? 0) }}"
               class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Stock Quantity *</label>
        <input type="number" min="0" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}" required
               class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Minimum Stock (alert level) *</label>
        <input type="number" min="0" name="minimum_stock" value="{{ old('minimum_stock', $product->minimum_stock ?? 0) }}" required
               class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
        <textarea name="description" rows="3"
                  class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">{{ old('description', $product->description ?? '') }}</textarea>
    </div>

    <div class="md:col-span-2">
        <label class="inline-flex items-center gap-2 text-sm">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true))
                   class="rounded text-blue-600">
            <span class="text-slate-700">Active</span>
        </label>
    </div>

</div>