<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'reference_no', 'expense_category_id', 'title', 'amount',
        'expense_date', 'payment_method', 'reference', 'notes', 'user_id',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount'       => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateReference(): string
    {
        $prefix = 'EXP-' . date('Ymd') . '-';
        $last   = self::where('reference_no', 'like', $prefix . '%')->latest('id')->first();
        $num    = $last ? ((int) substr($last->reference_no, -4)) + 1 : 1;
        return $prefix . str_pad($num, 4, '0', STR_PAD_LEFT);
    }
}