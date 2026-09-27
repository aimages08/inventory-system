<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SalePayment;
use Illuminate\Support\Facades\DB;

class SaleService
{
    public function __construct(private StockService $stock) {}

    public function createSale(array $data, array $items): Sale
    {
        return DB::transaction(function () use ($data, $items) {
            $sale = Sale::create([
                'reference_no' => Sale::generateReference(),
                'customer_id'  => $data['customer_id'],
                'sale_date'    => $data['sale_date'],
                'status'       => 'completed',
                'discount'     => $data['discount'] ?? 0,
                'shipping'     => $data['shipping'] ?? 0,
                'notes'        => $data['notes'] ?? null,
                'subtotal'     => 0, 'tax' => 0, 'total' => 0, 'paid' => 0, 'due' => 0,
            ]);

            $subtotal = 0; $taxTotal = 0;

            foreach ($items as $row) {
                $product = Product::findOrFail($row['product_id']);

                $qty      = (int) $row['quantity'];
                $unitPrice= (float) $row['unit_price'];
                $discount = (float) ($row['discount'] ?? 0);
                $tax      = (float) ($row['tax'] ?? 0);
                $lineTotal= ($qty * $unitPrice) - $discount + $tax;

                SaleItem::create([
                    'sale_id'    => $sale->id,
                    'product_id' => $product->id,
                    'quantity'   => $qty,
                    'unit_price' => $unitPrice,
                    'discount'   => $discount,
                    'tax'        => $tax,
                    'subtotal'   => $lineTotal,
                ]);

                // Auto deduct stock + log movement
                $this->stock->record(
                    $product, 'out', $qty, 'sale',
                    ['reference_id' => $sale->id, 'reference_type' => Sale::class]
                );

                $subtotal += ($qty * $unitPrice) - $discount;
                $taxTotal += $tax;
            }

            $total = $subtotal + $taxTotal + ($data['shipping'] ?? 0) - ($data['discount'] ?? 0);

            $sale->update([
                'subtotal' => $subtotal,
                'tax'      => $taxTotal,
                'total'    => $total,
                'due'      => $total,
            ]);

            // Customer owes us
            $customer = Customer::find($data['customer_id']);
            $customer->increment('current_balance', $total);

            // Initial payment
            if (!empty($data['paid_amount']) && $data['paid_amount'] > 0) {
                $this->recordPayment($sale, (float) $data['paid_amount'], [
                    'method'       => $data['payment_method'] ?? 'cash',
                    'payment_date' => $data['sale_date'],
                ]);
            }

            return $sale->fresh();
        });
    }

    public function recordPayment(Sale $sale, float $amount, array $meta = []): SalePayment
    {
        return DB::transaction(function () use ($sale, $amount, $meta) {
            $payment = SalePayment::create([
                'sale_id'      => $sale->id,
                'customer_id'  => $sale->customer_id,
                'amount'       => $amount,
                'method'       => $meta['method'] ?? 'cash',
                'reference'    => $meta['reference'] ?? null,
                'payment_date' => $meta['payment_date'] ?? now()->toDateString(),
                'notes'        => $meta['notes'] ?? null,
            ]);

            $sale->increment('paid', $amount);
            $sale->decrement('due', $amount);

            // Reduce customer's receivable
            $sale->customer->decrement('current_balance', $amount);

            return $payment;
        });
    }

    public function deleteSale(Sale $sale): void
    {
        DB::transaction(function () use ($sale) {
            // Restore stock
            foreach ($sale->items as $item) {
                $this->stock->record(
                    $item->product, 'in', $item->quantity, 'sale-delete',
                    ['reference_id' => $sale->id, 'reference_type' => Sale::class]
                );
            }

            // Reduce customer's balance by what's still due
            $sale->customer->decrement('current_balance', $sale->due);

            $sale->delete();
        });
    }
}