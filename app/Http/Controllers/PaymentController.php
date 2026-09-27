<?php

namespace App\Http\Controllers;

use App\Models\PurchasePayment;
use App\Models\SalePayment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input('type', 'all'); // all | received | made

        $salePayments     = $type !== 'made'     ? SalePayment::with(['sale', 'customer'])->get()     : collect();
        $purchasePayments = $type !== 'received' ? PurchasePayment::with(['purchase', 'supplier'])->get() : collect();

        // Combine into a single timeline
        $timeline = collect();

        foreach ($salePayments as $p) {
            $timeline->push([
                'date'       => $p->payment_date,
                'type'       => 'received',
                'party'      => $p->customer->name ?? '—',
                'reference'  => $p->sale->reference_no ?? '—',
                'method'     => $p->method,
                'amount'     => (float) $p->amount,
                'created_at' => $p->created_at,
            ]);
        }
        foreach ($purchasePayments as $p) {
            $timeline->push([
                'date'       => $p->payment_date,
                'type'       => 'made',
                'party'      => $p->supplier->name ?? '—',
                'reference'  => $p->purchase->reference_no ?? '—',
                'method'     => $p->method,
                'amount'     => (float) $p->amount,
                'created_at' => $p->created_at,
            ]);
        }

        $timeline = $timeline->sortByDesc('created_at')->values();

        $totals = [
            'received' => $salePayments->sum('amount'),
            'made'     => $purchasePayments->sum('amount'),
        ];

        return view('payments.index', compact('timeline', 'totals', 'type'));
    }
}