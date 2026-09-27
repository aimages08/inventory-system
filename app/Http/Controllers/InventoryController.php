<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use App\Services\StockService;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function __construct(private StockService $stock) {}

    /**
     * Current stock list with filters.
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'unit']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->input('status') === 'low') {
            $query->whereColumn('stock_quantity', '<=', 'minimum_stock')->where('stock_quantity', '>', 0);
        }
        if ($request->input('status') === 'out') {
            $query->where('stock_quantity', '<=', 0);
        }
        if ($request->input('status') === 'in') {
            $query->whereColumn('stock_quantity', '>', 'minimum_stock');
        }

        $products = $query->orderBy('name')->paginate(20)->withQueryString();

        $summary = [
            'total_products' => Product::count(),
            'low_stock'      => Product::whereColumn('stock_quantity', '<=', 'minimum_stock')->where('stock_quantity', '>', 0)->count(),
            'out_of_stock'   => Product::where('stock_quantity', '<=', 0)->count(),
            'stock_value'    => Product::selectRaw('SUM(stock_quantity * purchase_price) as v')->value('v') ?? 0,
        ];

        return view('inventory.index', compact('products', 'summary'));
    }

    /**
     * Stock history — all movements.
     */
    public function movements(Request $request)
    {
        $query = StockMovement::with(['product', 'user']);

        if ($productId = $request->input('product_id')) {
            $query->where('product_id', $productId);
        }
        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }
        if ($from = $request->input('from')) $query->whereDate('created_at', '>=', $from);
        if ($to   = $request->input('to'))   $query->whereDate('created_at', '<=', $to);

        $movements = $query->latest()->paginate(20)->withQueryString();
        $products  = Product::orderBy('name')->get();

        return view('inventory.movements', compact('movements', 'products'));
    }

    /**
     * Show adjustment form.
     */
    public function adjustForm(Product $product)
    {
        return view('inventory.adjust', compact('product'));
    }

    /**
     * Store adjustment.
     */
    public function adjust(Request $request, Product $product)
    {
        $data = $request->validate([
            'type'   => 'required|in:in,out,adjustment',
            'quantity' => 'required|integer|min:0',
            'reason' => 'nullable|string|max:100',
            'notes'  => 'nullable|string',
        ]);

        $this->stock->record(
            $product,
            $data['type'],
            (int) $data['quantity'],
            $data['reason'] ?? null,
            ['notes' => $data['notes'] ?? null]
        );

        return redirect()->route('inventory.index')
            ->with('success', 'Stock updated for ' . $product->name);
    }
}