<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product')->id;

        return [
            'name'           => 'required|string|max:191',
            'sku'            => ['required', 'string', 'max:100', Rule::unique('products', 'sku')->ignore($productId)],
            'barcode'        => ['nullable', 'string', 'max:100', Rule::unique('products', 'barcode')->ignore($productId)],
            'category_id'    => 'nullable|exists:categories,id',
            'brand_id'       => 'nullable|exists:brands,id',
            'unit_id'        => 'nullable|exists:units,id',
            'description'    => 'nullable|string',
            'image'          => 'nullable|image|max:2048',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price'  => 'required|numeric|min:0',
            'tax_rate'       => 'nullable|numeric|min:0|max:100',
            'stock_quantity' => 'required|integer|min:0',
            'minimum_stock'  => 'required|integer|min:0',
            'is_active'      => 'boolean',
        ];
    }
}