<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\PurchasePayment;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class PurchaseService
{
    /**
     * Create a new purchase with items and update stock + supplier balance.
     */
    public function createPurchase(array $data, array $items): Purchase
    {
        return DB::transaction(function () use ($data, $items) {
            $purchase = Purchase::create([
                'reference_no'  => Purchase::generateReference(),
                'supplier_id'   => $data['supplier_id'],
                'purchase_date' => $data['purchase_date'],
                'status'        => 'received',
                'discount'      => $data['discount'] ?? 0,
                'shipping'      => $data['shipping'] ?? 0,
                'notes'         => $data['notes'] ?? null,
                'subtotal'      => 0,
                'tax'           => 0,
                'total'         => 0,
                'paid'          => 0,
                'due'           => 0,
            ]);

            $subtotal = 0;
            $taxTotal = 0;

            foreach ($items as $row) {
                $product = Product::findOrFail($row['product_id']);

                $qty       = (int) $row['quantity'];
                $unitCost  = (float) $row['unit_cost'];
                $discount  = (float) ($row['discount'] ?? 0);
                $tax       = (float) ($row['tax'] ?? 0);
                $lineTotal = ($qty * $unitCost) - $discount + $tax;

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id'  => $product->id,
                    'quantity'    => $qty,
                    'unit_cost'   => $unitCost,
                    'discount'    => $discount,
                    'tax'         => $tax,
                    'subtotal'    => $lineTotal,
                ]);

                // Auto stock update
                app(\App\Services\StockService::class)->record(
    $product,
    'in',
    $qty,
    'purchase',
    ['reference_id' => $purchase->id, 'reference_type' => \App\Models\Purchase::class]
);

                // Optionally update product's purchase price to latest
                $product->update(['purchase_price' => $unitCost]);

                $subtotal += ($qty * $unitCost) - $discount;
                $taxTotal += $tax;
            }

            $total = $subtotal + $taxTotal + ($data['shipping'] ?? 0) - ($data['discount'] ?? 0);

            $purchase->update([
                'subtotal' => $subtotal,
                'tax'      => $taxTotal,
                'total'    => $total,
                'due'      => $total,
            ]);

            // Update supplier balance (we owe them)
            $supplier = Supplier::find($data['supplier_id']);
            $supplier->increment('current_balance', $total);

            // If initial payment was made
            if (!empty($data['paid_amount']) && $data['paid_amount'] > 0) {
                $this->recordPayment($purchase, (float) $data['paid_amount'], [
                    'method'       => $data['payment_method'] ?? 'cash',
                    'payment_date' => $data['purchase_date'],
                ]);
            }

            return $purchase->fresh();
        });
    }

    /**
     * Record a payment against a purchase and update supplier balance.
     */
    public function recordPayment(Purchase $purchase, float $amount, array $meta = []): PurchasePayment
    {
        return DB::transaction(function () use ($purchase, $amount, $meta) {
            $payment = PurchasePayment::create([
                'purchase_id'  => $purchase->id,
                'supplier_id'  => $purchase->supplier_id,
                'amount'       => $amount,
                'method'       => $meta['method'] ?? 'cash',
                'reference'    => $meta['reference'] ?? null,
                'payment_date' => $meta['payment_date'] ?? now()->toDateString(),
                'notes'        => $meta['notes'] ?? null,
            ]);

            $purchase->increment('paid', $amount);
            $purchase->decrement('due', $amount);

            // Reduce what we owe the supplier
            $purchase->supplier->decrement('current_balance', $amount);

            return $payment;
        });
    }

    /**
     * Delete a purchase and rollback stock + supplier balance.
     */
    public function deletePurchase(Purchase $purchase): void
    {
        DB::transaction(function () use ($purchase) {
            foreach ($purchase->items as $item) {
                app(\App\Services\StockService::class)->record(
    $item->product,
    'out',
    $item->quantity,
    'purchase-delete',
    ['reference_id' => $purchase->id, 'reference_type' => \App\Models\Purchase::class]
);
            }

            $purchase->supplier->decrement('current_balance', $purchase->due);

            $purchase->delete();
        });
    }
}