<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to   = $request->input('to',   now()->toDateString());

        // Sales & Purchases in date range
        $salesTotal     = Sale::whereBetween('sale_date', [$from, $to])->sum('total');
        $purchasesTotal = Purchase::whereBetween('purchase_date', [$from, $to])->sum('total');
        $expensesTotal  = Expense::whereBetween('expense_date', [$from, $to])->sum('amount');

        $profit = $salesTotal - $purchasesTotal - $expensesTotal;

        // Stock valuation
        $stockValue = Product::selectRaw('SUM(stock_quantity * purchase_price) as v')->value('v') ?? 0;
        $retailValue= Product::selectRaw('SUM(stock_quantity * selling_price) as v')->value('v') ?? 0;

        // Low stock
        $lowStock = Product::whereColumn('stock_quantity', '<=', 'minimum_stock')
            ->where('stock_quantity', '>', 0)->count();
        $outOfStock = Product::where('stock_quantity', '<=', 0)->count();

        // Top products (by sales quantity)
        $topProducts = DB::table('sale_items')
            ->join('products', 'products.id', '=', 'sale_items.product_id')
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->whereBetween('sales.sale_date', [$from, $to])
            ->select('products.name', DB::raw('SUM(sale_items.quantity) as qty'), DB::raw('SUM(sale_items.subtotal) as total'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('qty')
            ->limit(10)
            ->get();

        // Top customers
        $topCustomers = Sale::whereBetween('sale_date', [$from, $to])
            ->select('customer_id', DB::raw('SUM(total) as total'))
            ->groupBy('customer_id')
            ->with('customer')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Top suppliers
        $topSuppliers = Purchase::whereBetween('purchase_date', [$from, $to])
            ->select('supplier_id', DB::raw('SUM(total) as total'))
            ->groupBy('supplier_id')
            ->with('supplier')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $summary = compact(
            'from', 'to',
            'salesTotal', 'purchasesTotal', 'expensesTotal', 'profit',
            'stockValue', 'retailValue', 'lowStock', 'outOfStock',
            'topProducts', 'topCustomers', 'topSuppliers'
        );

        return view('reports.index', $summary);
    }
}