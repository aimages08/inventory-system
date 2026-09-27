<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\ExpenseCategory;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\User;
use App\Services\PurchaseService;
use App\Services\SaleService;
use App\Services\StockService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();
        if (! $admin) {
            $this->command->warn('No user found. Run AdminUserSeeder first.');
            return;
        }

        /* ===============================
         * 1) UNITS
         * =============================== */
        $units = [
            ['name' => 'Piece',    'short_name' => 'pc'],
            ['name' => 'Kilogram', 'short_name' => 'kg'],
            ['name' => 'Box',      'short_name' => 'box'],
            ['name' => 'Litre',    'short_name' => 'L'],
            ['name' => 'Dozen',    'short_name' => 'dz'],
        ];
        foreach ($units as $u) {
            Unit::firstOrCreate(['name' => $u['name']], $u + ['is_active' => true]);
        }

        /* ===============================
         * 2) CATEGORIES
         * =============================== */
        $categories = [
            'Electronics', 'Clothing', 'Food & Beverage', 'Home & Kitchen',
            'Stationery', 'Sports', 'Toys', 'Health & Beauty',
        ];
        foreach ($categories as $name) {
            Category::firstOrCreate(['name' => $name], [
                'slug' => Str::slug($name) . '-' . Str::random(4),
                'is_active' => true,
            ]);
        }

        /* ===============================
         * 3) BRANDS
         * =============================== */
        $brands = [
            'Samsung', 'Apple', 'Nike', 'Adidas', 'Sony', 'LG',
            'Generic', 'Xiaomi', 'HP', 'Dell',
        ];
        foreach ($brands as $name) {
            Brand::firstOrCreate(['name' => $name], [
                'slug' => Str::slug($name) . '-' . Str::random(4),
                'is_active' => true,
            ]);
        }

        /* ===============================
         * 4) EXPENSE CATEGORIES
         * =============================== */
        $expCats = [
            'Rent', 'Utilities', 'Salaries', 'Transport',
            'Marketing', 'Office Supplies', 'Maintenance', 'Insurance',
        ];
        foreach ($expCats as $name) {
            ExpenseCategory::firstOrCreate(['name' => $name], ['is_active' => true]);
        }

        /* ===============================
         * 5) SUPPLIERS
         * =============================== */
        $supplierNames = [
            ['Acme Distributors',   'Acme Pvt Ltd',     'sales@acme.test',      'Karachi'],
            ['Global Traders',      'Global Traders',   'info@global.test',     'Lahore'],
            ['Prime Wholesale',     'Prime Wholesale',  'contact@prime.test',   'Islamabad'],
            ['Metro Supplies',      'Metro Supplies',   'hello@metro.test',     'Faisalabad'],
            ['Eastern Imports',     'Eastern Imports',  'info@eastern.test',    'Multan'],
            ['Sunrise Traders',     'Sunrise Traders',  'sales@sunrise.test',   'Peshawar'],
        ];
        foreach ($supplierNames as $s) {
            Supplier::firstOrCreate(['name' => $s[0]], [
                'company'    => $s[1],
                'email'      => $s[2],
                'phone'      => '+92' . rand(3000000000, 3999999999),
                'city'       => $s[3],
                'country'    => 'Pakistan',
                'is_active'  => true,
            ]);
        }

        /* ===============================
         * 6) CUSTOMERS
         * =============================== */
        $customerNames = [
            'Walk-in Customer', 'Ahmed Retail', 'Bilal Store', 'Farah Mart',
            'Zain Supermarket', 'Hassan Traders', 'Kiran Electronics',
            'Salman Furniture', 'Ayesha Boutique', 'Usman Grocery',
            'Nadia Cosmetics', 'Tariq Mobiles', 'Sana Sports',
            'Rehan Books', 'Mehwish Fashion', 'Ali Hardware',
        ];
        foreach ($customerNames as $name) {
            Customer::firstOrCreate(['name' => $name], [
                'email'        => Str::slug($name) . '@example.test',
                'phone'        => '+92' . rand(3000000000, 3999999999),
                'city'         => ['Karachi', 'Lahore', 'Islamabad', 'Faisalabad'][rand(0, 3)],
                'country'      => 'Pakistan',
                'credit_limit' => rand(1, 10) * 10000,
                'is_active'    => true,
            ]);
        }

        /* ===============================
         * 7) PRODUCTS (60 rows — for pagination)
         * =============================== */
        $productTemplates = [
            ['Samsung 24" Monitor',      'Electronics',      'Samsung',  18000,  24000],
            ['Samsung 32" Monitor',      'Electronics',      'Samsung',  28000,  36000],
            ['Apple MacBook Air M2',     'Electronics',      'Apple',   220000, 260000],
            ['Apple iPhone 15',          'Electronics',      'Apple',   200000, 240000],
            ['Apple AirPods Pro',        'Electronics',      'Apple',    45000,  58000],
            ['Nike Running Shoes',       'Sports',           'Nike',      8000,  12500],
            ['Nike Sports Bag',          'Sports',           'Nike',      3500,   5500],
            ['Adidas Sneakers',          'Sports',           'Adidas',    7500,  11000],
            ['Adidas Football',          'Sports',           'Adidas',    2200,   3500],
            ['Sony Headphones WH-1000',  'Electronics',      'Sony',     38000,  48000],
            ['Sony Bluetooth Speaker',   'Electronics',      'Sony',      6500,   9500],
            ['LG 43" Smart TV',          'Electronics',      'LG',       65000,  85000],
            ['LG Microwave Oven',        'Home & Kitchen',   'LG',       18000,  24000],
            ['HP Laptop 15"',            'Electronics',      'HP',       85000, 110000],
            ['HP Printer LaserJet',      'Electronics',      'HP',       32000,  42000],
            ['Dell Inspiron 14',         'Electronics',      'Dell',     92000, 118000],
            ['Dell Wireless Keyboard',   'Electronics',      'Dell',      2200,   3500],
            ['Xiaomi Redmi Note 13',     'Electronics',      'Xiaomi',   48000,  58000],
            ['Xiaomi Power Bank 20000',  'Electronics',      'Xiaomi',    3200,   4800],
            ['USB-C Cable 2m',           'Electronics',      'Generic',    200,    500],
            ['USB-C Charger 65W',        'Electronics',      'Generic',   1800,   2800],
            ['Wireless Mouse',           'Electronics',      'Generic',    800,   1500],
            ['Wired Mouse',              'Electronics',      'Generic',    300,    700],
            ['Mechanical Keyboard',      'Electronics',      'Generic',   5500,   8500],
            ['Webcam HD 1080p',          'Electronics',      'Generic',   3500,   5500],
            ['Men\'s Formal Shirt',      'Clothing',         'Generic',   1200,   2200],
            ['Men\'s Jeans',             'Clothing',         'Generic',   1800,   3200],
            ['Women\'s Handbag',         'Clothing',         'Generic',   2500,   4500],
            ['Winter Jacket',            'Clothing',         'Generic',   4500,   7500],
            ['Cotton T-Shirt',           'Clothing',         'Generic',    600,   1200],
            ['Rice Basmati 5kg',         'Food & Beverage',  'Generic',   1400,   1900],
            ['Cooking Oil 5L',           'Food & Beverage',  'Generic',   2200,   2800],
            ['Sugar 1kg',                'Food & Beverage',  'Generic',    140,    180],
            ['Tea Leaves 1kg',           'Food & Beverage',  'Generic',    850,   1200],
            ['Coffee Beans 500g',        'Food & Beverage',  'Generic',   1200,   1800],
            ['Non-Stick Frying Pan',     'Home & Kitchen',   'Generic',   1800,   2800],
            ['Pressure Cooker 5L',       'Home & Kitchen',   'Generic',   3200,   4800],
            ['Dinner Set 24pc',          'Home & Kitchen',   'Generic',   5500,   8500],
            ['Electric Kettle 1.7L',     'Home & Kitchen',   'Generic',   2200,   3500],
            ['Water Bottle 1L',          'Home & Kitchen',   'Generic',    300,    550],
            ['Notebook A5 200pg',        'Stationery',       'Generic',    180,    350],
            ['Ball Pen Pack 10',         'Stationery',       'Generic',    200,    400],
            ['Stapler Heavy Duty',       'Stationery',       'Generic',    450,    800],
            ['File Folder Pack 12',      'Stationery',       'Generic',    350,    650],
            ['Whiteboard Marker Set',    'Stationery',       'Generic',    450,    800],
            ['Yoga Mat Premium',         'Sports',           'Generic',   1500,   2500],
            ['Dumbbell 5kg Pair',        'Sports',           'Generic',   2200,   3500],
            ['Skipping Rope',            'Sports',           'Generic',    350,    700],
            ['Cricket Bat English Willow','Sports',          'Generic',   6500,   9500],
            ['Cricket Ball Leather',     'Sports',           'Generic',    650,   1100],
            ['Kids Puzzle 100pc',        'Toys',             'Generic',    550,    950],
            ['Remote Control Car',       'Toys',             'Generic',   2200,   3800],
            ['Building Blocks Set',      'Toys',             'Generic',   1800,   3200],
            ['Shampoo 400ml',            'Health & Beauty',  'Generic',    550,    950],
            ['Face Wash 150ml',          'Health & Beauty',  'Generic',    450,    800],
            ['Body Lotion 500ml',        'Health & Beauty',  'Generic',    750,   1300],
            ['Toothpaste 150g',          'Health & Beauty',  'Generic',    250,    450],
            ['Hand Sanitizer 500ml',     'Health & Beauty',  'Generic',    400,    700],
            ['Vitamins C 60tabs',        'Health & Beauty',  'Generic',    850,   1400],
            ['Band-Aid Pack 50',         'Health & Beauty',  'Generic',    200,    400],
        ];

        $pieceUnit = Unit::where('short_name', 'pc')->first();
        $boxUnit   = Unit::where('short_name', 'box')->first();
        $unitPicker = [$pieceUnit, $boxUnit];

        foreach ($productTemplates as $i => $tpl) {
            [$pname, $catName, $brandName, $cost, $price] = $tpl;
            $cat   = Category::where('name', $catName)->first();
            $brand = Brand::where('name', $brandName)->first();
            $sku   = 'SKU-' . str_pad($i + 1, 5, '0', STR_PAD_LEFT);
            $stock = rand(0, 80); // some will be 0 (out of stock), some low
            $min   = rand(5, 15);

            Product::firstOrCreate(['sku' => $sku], [
                'name'           => $pname,
                'slug'           => Str::slug($pname) . '-' . Str::random(4),
                'barcode'        => 'BC' . str_pad($i + 1, 10, '0', STR_PAD_LEFT),
                'category_id'    => $cat?->id,
                'brand_id'       => $brand?->id,
                'unit_id'        => $unitPicker[$i % 2]?->id,
                'purchase_price' => $cost,
                'selling_price'  => $price,
                'tax_rate'       => [0, 5, 10, 15][rand(0, 3)],
                'stock_quantity' => $stock,
                'minimum_stock'  => $min,
                'is_active'      => true,
            ]);
        }

        /* ===============================
         * 8) PURCHASES (25 rows)
         * =============================== */
        $purchaseService = app(PurchaseService::class);
        $allSuppliers    = Supplier::all();
        $allProducts     = Product::all();

        if ($purchaseService && $allSuppliers->count() && $allProducts->count()) {
            for ($i = 0; $i < 25; $i++) {
                $supplier = $allSuppliers->random();
                $items = [];
                $numItems = rand(1, 4);
                for ($j = 0; $j < $numItems; $j++) {
                    $product = $allProducts->random();
                    $items[] = [
                        'product_id' => $product->id,
                        'quantity'   => rand(5, 30),
                        'unit_cost'  => $product->purchase_price,
                        'discount'   => 0,
                        'tax'        => 0,
                    ];
                }

                try {
                    $purchaseService->createPurchase([
                        'supplier_id'    => $supplier->id,
                        'purchase_date'  => now()->subDays(rand(1, 90))->toDateString(),
                        'discount'       => [0, 0, 500, 1000][rand(0, 3)],
                        'shipping'       => [0, 100, 250, 500][rand(0, 3)],
                        'paid_amount'    => rand(0, 1) ? rand(5000, 50000) : 0,
                        'payment_method' => ['cash', 'bank', 'card'][rand(0, 2)],
                        'notes'          => "Auto-generated demo purchase #" . ($i + 1),
                    ], $items);
                } catch (\Throwable $e) {
                    // skip failures silently for demo
                }
            }
        }

        /* ===============================
         * 9) SALES (50 rows)
         * =============================== */
        $saleService  = app(SaleService::class);
        $allCustomers = Customer::all();

        if ($saleService && $allCustomers->count() && $allProducts->count()) {
            for ($i = 0; $i < 50; $i++) {
                $customer = $allCustomers->random();
                $items = [];
                $numItems = rand(1, 5);
                for ($j = 0; $j < $numItems; $j++) {
                    $product = $allProducts->random();
                    // skip if stock is 0 to avoid negative
                    if ($product->stock_quantity <= 0) continue;
                    $qty = min(rand(1, 5), $product->stock_quantity);
                    $items[] = [
                        'product_id' => $product->id,
                        'quantity'   => $qty,
                        'unit_price' => $product->selling_price,
                        'discount'   => 0,
                        'tax'        => 0,
                    ];
                }

                if (empty($items)) continue;

                try {
                    $saleService->createSale([
                        'customer_id'    => $customer->id,
                        'sale_date'      => now()->subDays(rand(0, 60))->toDateString(),
                        'discount'       => [0, 0, 100, 250][rand(0, 3)],
                        'shipping'       => [0, 50, 100, 200][rand(0, 3)],
                        'paid_amount'    => rand(0, 1) ? rand(2000, 50000) : 0,
                        'payment_method' => ['cash', 'bank', 'card', 'online'][rand(0, 3)],
                        'notes'          => "Auto-generated demo sale #" . ($i + 1),
                    ], $items);
                } catch (\Throwable $e) {
                    // skip failures silently for demo
                }
            }
        }

        /* ===============================
         * 10) EXPENSES (30 rows)
         * =============================== */
        $expCatsAll = ExpenseCategory::all();
        if ($expCatsAll->count()) {
            for ($i = 0; $i < 30; $i++) {
                $cat = $expCatsAll->random();
                \App\Models\Expense::create([
                    'reference_no'         => 'EXP-' . now()->format('Ymd') . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                    'expense_category_id'  => $cat->id,
                    'title'                => $cat->name . ' — ' . now()->subDays($i)->format('d M'),
                    'amount'               => rand(500, 30000),
                    'expense_date'         => now()->subDays(rand(0, 60))->toDateString(),
                    'payment_method'       => ['cash', 'bank', 'card'][rand(0, 2)],
                    'reference'            => 'REF' . rand(1000, 9999),
                    'notes'                => 'Auto-generated demo expense',
                    'user_id'              => $admin->id,
                ]);
            }
        }

        $this->command->info('✅ Demo data seeded successfully!');
        $this->command->info('   Categories: ' . Category::count());
        $this->command->info('   Brands: ' . Brand::count());
        $this->command->info('   Units: ' . Unit::count());
        $this->command->info('   Products: ' . Product::count());
        $this->command->info('   Suppliers: ' . Supplier::count());
        $this->command->info('   Customers: ' . Customer::count());
        $this->command->info('   Purchases: ' . Purchase::count());
        $this->command->info('   Sales: ' . \App\Models\Sale::count());
        $this->command->info('   Expenses: ' . \App\Models\Expense::count());
    }
}