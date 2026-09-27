<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->route('customer')->id;

        return [
            'name'            => 'required|string|max:191',
            'company'         => 'nullable|string|max:191',
            'email'           => ['nullable', 'email', 'max:191', Rule::unique('customers', 'email')->ignore($id)],
            'phone'           => 'nullable|string|max:50',
            'tax_number'      => 'nullable|string|max:50',
            'address'         => 'nullable|string',
            'city'            => 'nullable|string|max:100',
            'country'         => 'nullable|string|max:100',
            'opening_balance' => 'nullable|numeric',
            'credit_limit'    => 'nullable|numeric|min:0',
            'notes'           => 'nullable|string',
            'is_active'       => 'boolean',
        ];
    }
}