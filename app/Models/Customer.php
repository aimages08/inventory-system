<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name', 'company', 'email', 'phone', 'tax_number',
        'address', 'city', 'country',
        'opening_balance', 'current_balance', 'credit_limit',
        'notes', 'is_active',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'credit_limit'    => 'decimal:2',
        'is_active'       => 'boolean',
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function payments()
    {
        return $this->hasMany(SalePayment::class);
    }
}