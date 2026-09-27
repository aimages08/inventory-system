<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    protected $fillable = [
        'purchase_id', 'product_id',
        'quantity', 'unit_cost', 'discount', 'tax', 'subtotal',
    ];

    protected $casts = [
        'unit_cost' => 'decimal:2',
        'discount'  => 'decimal:2',
        'tax'       => 'decimal:2',
        'subtotal'  => 'decimal:2',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}