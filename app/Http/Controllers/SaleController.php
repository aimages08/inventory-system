<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSaleRequest;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Services\SaleService;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function __construct(private SaleService $service) {}

    public function index(Request $request)
    {
        $query = Sale::with('customer');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('reference_no', 'like', "%{$search}%")
                  ->orWhereHas('customer', fn ($c) => $c->where('name', 'like', "%{$search}%"));
            });
        }

        if ($customerId = $request->input('customer_id')) {
            $query->where('customer_id', $customerId);
        }
        if ($from = $request->input('from')) $query->whereDate('sale_date', '>=', $from);
        if ($to   = $request->input('to'))   $query->whereDate('sale_date', '<=', $to);

        $sales     = $query->latest('sale_date')->latest('id')->paginate(15)->withQueryString();
        $customers = Customer::orderBy('name')->get();

        return view('sales.index', compact('sales', 'customers'));
    }

    public function create()
    {
        $customers = Customer::where('is_active', true)->orderBy('name')->get();
        $products  = Product::where('is_active', true)->orderBy('name')->get();

        return view('sales.create', compact('customers', 'products'));
    }

    public function store(StoreSaleRequest $request)
    {
        $sale = $this->service->createSale(
            $request->validated(),
            $request->input('items')
        );

        return redirect()->route('sales.show', $sale)
            ->with('success', 'Sale created successfully.');
    }

    public function show(Sale $sale)
    {
        $sale->load(['customer', 'items.product', 'payments']);
        return view('sales.show', compact('sale'));
    }

    public function destroy(Sale $sale)
    {
        $this->service->deleteSale($sale);
        return redirect()->route('sales.index')->with('success', 'Sale deleted.');
    }

    public function addPayment(Request $request, Sale $sale)
    {
        $data = $request->validate([
            'amount'       => 'required|numeric|min:0.01|max:' . $sale->due,
            'method'       => 'required|in:cash,bank,card,online',
            'reference'    => 'nullable|string|max:100',
            'payment_date' => 'required|date',
            'notes'        => 'nullable|string',
        ]);

        $this->service->recordPayment($sale, (float) $data['amount'], $data);

        return back()->with('success', 'Payment recorded.');
    }
}