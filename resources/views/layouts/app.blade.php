<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body class="h-full bg-slate-100 text-slate-800 antialiased">

<div x-data="{ sidebarOpen: false }" class="min-h-full">

    {{-- SIDEBAR --}}
    <aside
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        class="fixed inset-y-0 left-0 z-40 w-64 bg-slate-800 text-slate-300 transform transition-transform duration-200 ease-in-out overflow-y-auto">

        <div class="h-16 flex items-center gap-3 px-5 border-b border-slate-700">
           @if(setting('logo'))
    <img src="{{ asset('storage/' . setting('logo')) }}" class="h-8 w-8 rounded object-cover">
@else
    <i class="bi bi-box-seam-fill text-blue-500 text-xl"></i>
@endif
<span class="font-bold text-white text-base">{{ setting('business_name', config('app.name')) }}</span>
        </div>

        <nav class="py-3 text-sm">

            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-5 py-2.5 hover:bg-slate-700 hover:text-white border-l-4 {{ request()->routeIs('dashboard') ? 'bg-slate-700 text-white border-blue-500' : 'border-transparent' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <div class="px-5 pt-4 pb-1 text-[11px] uppercase tracking-wider text-slate-500">Catalog</div>
            <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-5 py-2.5 hover:bg-slate-700 hover:text-white border-l-4 {{ request()->routeIs('products.*') ? 'bg-slate-700 text-white border-blue-500' : 'border-transparent' }}"><i class="bi bi-box"></i> Products</a>
            <a href="{{ route('categories.index') }}" class="flex items-center gap-3 px-5 py-2.5 hover:bg-slate-700 hover:text-white border-l-4 {{ request()->routeIs('categories.*') ? 'bg-slate-700 text-white border-blue-500' : 'border-transparent' }}"><i class="bi bi-tags"></i> Categories</a>
            <a href="{{ route('brands.index') }}" class="flex items-center gap-3 px-5 py-2.5 hover:bg-slate-700 hover:text-white border-l-4 {{ request()->routeIs('brands.*') ? 'bg-slate-700 text-white border-blue-500' : 'border-transparent' }}"><i class="bi bi-bookmark"></i> Brands</a>
            <a href="{{ route('units.index') }}" class="flex items-center gap-3 px-5 py-2.5 hover:bg-slate-700 hover:text-white border-l-4 {{ request()->routeIs('units.*') ? 'bg-slate-700 text-white border-blue-500' : 'border-transparent' }}"><i class="bi bi-rulers"></i> Units</a>

            <div class="px-5 pt-4 pb-1 text-[11px] uppercase tracking-wider text-slate-500">Inventory</div>
            <a href="{{ route('inventory.index') }}" class="flex items-center gap-3 px-5 py-2.5 hover:bg-slate-700 hover:text-white border-l-4 {{ request()->routeIs('inventory.*') ? 'bg-slate-700 text-white border-blue-500' : 'border-transparent' }}"><i class="bi bi-boxes"></i> Stock</a>

            <div class="px-5 pt-4 pb-1 text-[11px] uppercase tracking-wider text-slate-500">Purchases & Sales</div>
            <a href="{{ route('suppliers.index') }}" class="flex items-center gap-3 px-5 py-2.5 hover:bg-slate-700 hover:text-white border-l-4 {{ request()->routeIs('suppliers.*') ? 'bg-slate-700 text-white border-blue-500' : 'border-transparent' }}"><i class="bi bi-truck"></i> Suppliers</a>
            <a href="{{ route('purchases.index') }}" class="flex items-center gap-3 px-5 py-2.5 hover:bg-slate-700 hover:text-white border-l-4 {{ request()->routeIs('purchases.*') ? 'bg-slate-700 text-white border-blue-500' : 'border-transparent' }}"><i class="bi bi-cart-plus"></i> Purchases</a>
            <a href="{{ route('customers.index') }}" class="flex items-center gap-3 px-5 py-2.5 hover:bg-slate-700 hover:text-white border-l-4 {{ request()->routeIs('customers.*') ? 'bg-slate-700 text-white border-blue-500' : 'border-transparent' }}"><i class="bi bi-people"></i> Customers</a>
            <a href="{{ route('sales.index') }}" class="flex items-center gap-3 px-5 py-2.5 hover:bg-slate-700 hover:text-white border-l-4 {{ request()->routeIs('sales.*') ? 'bg-slate-700 text-white border-blue-500' : 'border-transparent' }}"><i class="bi bi-cart-check"></i> Sales</a>

            <div class="px-5 pt-4 pb-1 text-[11px] uppercase tracking-wider text-slate-500">Finance</div>
            <a href="{{ route('payments.index') }}" class="flex items-center gap-3 px-5 py-2.5 hover:bg-slate-700 hover:text-white border-l-4 {{ request()->routeIs('payments.*') ? 'bg-slate-700 text-white border-blue-500' : 'border-transparent' }}"><i class="bi bi-cash-coin"></i> Payments</a>
            <a href="{{ route('expenses.index') }}" class="flex items-center gap-3 px-5 py-2.5 hover:bg-slate-700 hover:text-white border-l-4 {{ request()->routeIs('expenses.*') ? 'bg-slate-700 text-white border-blue-500' : 'border-transparent' }}"><i class="bi bi-receipt"></i> Expenses</a>

            <div class="px-5 pt-4 pb-1 text-[11px] uppercase tracking-wider text-slate-500">Reports</div>
            <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-5 py-2.5 hover:bg-slate-700 hover:text-white border-l-4 {{ request()->routeIs('reports.*') ? 'bg-slate-700 text-white border-blue-500' : 'border-transparent' }}"><i class="bi bi-graph-up"></i> Reports</a>

            <div class="px-5 pt-4 pb-1 text-[11px] uppercase tracking-wider text-slate-500">System</div>
            <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-5 py-2.5 hover:bg-slate-700 hover:text-white border-l-4 {{ request()->routeIs('settings.*') ? 'bg-slate-700 text-white border-blue-500' : 'border-transparent' }}"><i class="bi bi-gear"></i> Settings</a>
        </nav>
    </aside>

    <div x-show="sidebarOpen" @click="sidebarOpen = false"
         x-transition.opacity
         class="fixed inset-0 bg-black/50 z-30 lg:hidden"></div>

    <div class="lg:pl-64">

        <header class="fixed top-0 right-0 left-0 lg:left-64 h-16 bg-white border-b border-slate-200 flex items-center px-4 sm:px-6 z-20">
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-2xl text-slate-600 mr-3">
                <i class="bi bi-list"></i>
            </button>

            <div class="font-semibold text-slate-700">@yield('title', 'Dashboard')</div>

            <div class="ml-auto flex items-center gap-4">
                <div class="hidden sm:flex items-center text-sm text-slate-500">
                    <i class="bi bi-calendar3 me-1"></i>{{ now()->format('d M Y') }}
                </div>

                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2 text-sm text-slate-700 hover:text-slate-900">
                        <i class="bi bi-person-circle text-xl"></i>
                        <span class="hidden sm:inline">{{ auth()->user()->name ?? 'Guest' }}</span>
                        <i class="bi bi-chevron-down text-xs"></i>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-transition
                         class="absolute right-0 mt-2 w-48 bg-white border border-slate-200 rounded-lg shadow-lg py-1 text-sm z-30">
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 hover:bg-slate-50">
                            <i class="bi bi-person"></i> Profile
                        </a>
                        <hr class="border-slate-100">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2 text-red-600 hover:bg-red-50">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="pt-16">
            <div class="p-4 sm:p-6">

                @if(session('success'))
                    <div class="mb-4 flex items-center gap-2 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                        <i class="bi bi-check-circle-fill"></i>{{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 flex items-center gap-2 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                        <i class="bi bi-x-circle-fill"></i>{{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</div>


@stack('scripts')

</body>
</html>