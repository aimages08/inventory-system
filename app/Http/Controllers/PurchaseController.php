<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseRequest;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Services\PurchaseService;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function __construct(private PurchaseService $service) {}

    public function index(Request $request)
    {
        $query = Purchase::with('supplier');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('reference_no', 'like', "%{$search}%")
                  ->orWhereHas('supplier', fn ($s) => $s->where('name', 'like', "%{$search}%"));
            });
        }

        if ($supplier = $request->input('supplier_id')) {
            $query->where('supplier_id', $supplier);
        }

        if ($from = $request->input('from')) $query->whereDate('purchase_date', '>=', $from);
        if ($to   = $request->input('to'))   $query->whereDate('purchase_date', '<=', $to);

        $purchases = $query->latest('purchase_date')->latest('id')->paginate(15)->withQueryString();
        $suppliers = Supplier::orderBy('name')->get();

        return view('purchases.index', compact('purchases', 'suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        $products  = Product::where('is_active', true)->orderBy('name')->get();

        return view('purchases.create', compact('suppliers', 'products'));
    }

    public function store(StorePurchaseRequest $request)
    {
        $purchase = $this->service->createPurchase(
            $request->validated(),
            $request->input('items')
        );

        return redirect()->route('purchases.show', $purchase)
            ->with('success', 'Purchase created successfully.');
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'items.product', 'payments']);
        return view('purchases.show', compact('purchase'));
    }

    public function destroy(Purchase $purchase)
    {
        $this->service->deletePurchase($purchase);
        return redirect()->route('purchases.index')->with('success', 'Purchase deleted.');
    }

    /**
     * Add a payment to an existing purchase.
     */
    public function addPayment(Request $request, Purchase $purchase)
    {
        $data = $request->validate([
            'amount'       => 'required|numeric|min:0.01|max:' . $purchase->due,
            'method'       => 'required|in:cash,bank,card,online',
            'reference'    => 'nullable|string|max:100',
            'payment_date' => 'required|date',
            'notes'        => 'nullable|string',
        ]);

        $this->service->recordPayment($purchase, (float) $data['amount'], $data);

        return back()->with('success', 'Payment recorded.');
    }
}