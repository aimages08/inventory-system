@php($brand = $brand ?? null)

<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Name *</label>
        <input type="text" name="name" value="{{ old('name', $brand->name ?? '') }}" required
               class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
        @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
        <textarea name="description" rows="3" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">{{ old('description', $brand->description ?? '') }}</textarea>
    </div>
    <div>
        <label class="inline-flex items-center gap-2 text-sm">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $brand->is_active ?? true)) class="rounded text-blue-600">
            <span>Active</span>
        </label>
    </div>
</div>