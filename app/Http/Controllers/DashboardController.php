<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        $stats = [
            'total_products'     => Product::count(),
            'total_stock_value'  => Product::selectRaw('SUM(stock_quantity * purchase_price) as v')->value('v') ?? 0,
            'low_stock_count'    => Product::whereColumn('stock_quantity', '<=', 'minimum_stock')->where('stock_quantity', '>', 0)->count(),
            'out_of_stock_count' => Product::where('stock_quantity', '<=', 0)->count(),
            'today_purchases'    => Purchase::whereDate('purchase_date', $today)->sum('total'),
            'today_sales'        => Sale::whereDate('sale_date', $today)->sum('total'),
            'total_suppliers'    => Supplier::count(),
            'total_customers'    => Customer::count(),
            'total_expenses'     => Expense::sum('amount'),
            'supplier_dues'      => Supplier::sum('current_balance'),
            'customer_dues'      => Customer::sum('current_balance'),
        ];

        // Last 7 days sales/purchase for chart
        $chart = [
            'labels'   => [],
            'sales'    => [],
            'purchases'=> [],
        ];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $chart['labels'][]    = now()->subDays($i)->format('d M');
            $chart['sales'][]     = (float) Sale::whereDate('sale_date', $date)->sum('total');
            $chart['purchases'][] = (float) Purchase::whereDate('purchase_date', $date)->sum('total');
        }

        // Recent sales
        $recentSales = Sale::with('customer')->latest('id')->limit(5)->get();

        // Low stock products
        $lowStockProducts = Product::whereColumn('stock_quantity', '<=', 'minimum_stock')
            ->where('stock_quantity', '>', 0)
            ->orderBy('stock_quantity')
            ->limit(5)
            ->get();

        return view('dashboard', compact('stats', 'chart', 'recentSales', 'lowStockProducts'));
    }
}