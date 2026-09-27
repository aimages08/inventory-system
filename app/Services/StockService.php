<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockService
{
    /**
     * Record a stock movement and update product's stock_quantity.
     *
     * $type:  'in' | 'out' | 'adjustment'
     * $qty:   absolute quantity (positive integer)
     */
    public function record(
        Product $product,
        string $type,
        int $qty,
        ?string $reason = null,
        array $meta = []
    ): StockMovement {
        return DB::transaction(function () use ($product, $type, $qty, $reason, $meta) {
            $before = $product->stock_quantity;

            $after = match ($type) {
                'in'         => $before + $qty,
                'out'        => max(0, $before - $qty),
                'adjustment' => $qty,  // absolute set
                default      => $before,
            };

            $product->update(['stock_quantity' => $after]);

            return StockMovement::create([
                'product_id'     => $product->id,
                'type'           => $type,
                'quantity'       => $qty,
                'stock_before'   => $before,
                'stock_after'    => $after,
                'reason'         => $reason ?? $meta['reason'] ?? null,
                'reference_id'   => $meta['reference_id'] ?? null,
                'reference_type' => $meta['reference_type'] ?? null,
                'notes'          => $meta['notes'] ?? null,
                'user_id'        => Auth::id(),
            ]);
        });
    }

    /**
     * Adjust stock to a target value (records the delta).
     */
    public function adjust(Product $product, int $newQty, ?string $reason = null, ?string $notes = null): StockMovement
    {
        return $this->record($product, 'adjustment', $newQty, $reason, ['notes' => $notes]);
    }
}