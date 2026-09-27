<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSupplierRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->route('supplier')->id;

        return [
            'name'            => 'required|string|max:191',
            'company'         => 'nullable|string|max:191',
            'email'           => ['nullable', 'email', 'max:191', Rule::unique('suppliers', 'email')->ignore($id)],
            'phone'           => 'nullable|string|max:50',
            'tax_number'      => 'nullable|string|max:50',
            'address'         => 'nullable|string',
            'city'            => 'nullable|string|max:100',
            'country'         => 'nullable|string|max:100',
            'opening_balance' => 'nullable|numeric',
            'notes'           => 'nullable|string',
            'is_active'       => 'boolean',
        ];
    }
}