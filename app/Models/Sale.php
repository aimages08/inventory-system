<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'reference_no', 'customer_id', 'sale_date', 'status',
        'subtotal', 'discount', 'tax', 'shipping', 'total',
        'paid', 'due', 'notes',
    ];

    protected $casts = [
        'sale_date' => 'date',
        'subtotal'  => 'decimal:2',
        'discount'  => 'decimal:2',
        'tax'       => 'decimal:2',
        'shipping'  => 'decimal:2',
        'total'     => 'decimal:2',
        'paid'      => 'decimal:2',
        'due'       => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function payments()
    {
        return $this->hasMany(SalePayment::class);
    }

    public static function generateReference(): string
    {
        $prefix = 'SAL-' . date('Ymd') . '-';
        $last   = self::where('reference_no', 'like', $prefix . '%')->latest('id')->first();
        $num    = $last ? ((int) substr($last->reference_no, -4)) + 1 : 1;
        return $prefix . str_pad($num, 4, '0', STR_PAD_LEFT);
    }
}