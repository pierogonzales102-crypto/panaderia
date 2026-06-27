<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Admin') — Panis & Co</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700|playfair-display:400,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-panis-100 min-h-screen">
    <div class="flex min-h-screen">
        <aside class="w-64 bg-panis-900 text-white flex-shrink-0 hidden lg:flex flex-col">
            <div class="p-6 border-b border-panis-800">
                <a href="{{ route('admin.dashboard') }}" class="font-serif text-xl font-bold text-gold-400">🥖 Panis & Co</a>
                <p class="text-xs text-panis-400 mt-1">Panel de Administración</p>
            </div>
            <nav class="flex-1 p-4 space-y-1 text-sm">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-panis-800 transition {{ request()->routeIs('admin.dashboard') ? 'bg-panis-800 text-gold-400' : '' }}">
                    📊 Dashboard
                </a>
                @if(auth()->user()->hasAnyRole(['administrador','gerente','moderador_web']))
                <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-panis-800 transition {{ request()->routeIs('admin.products.*') ? 'bg-panis-800 text-gold-400' : '' }}">
                    🍞 Productos
                </a>
                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-panis-800 transition {{ request()->routeIs('admin.categories.*') ? 'bg-panis-800 text-gold-400' : '' }}">
                    📁 Categorías
                </a>
                @endif
                @if(auth()->user()->hasAnyRole(['administrador','gerente','ventas','moderador_web']))
                <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-panis-800 transition {{ request()->routeIs('admin.orders.*') ? 'bg-panis-800 text-gold-400' : '' }}">
                    📦 Pedidos
                </a>
                <a href="{{ route('admin.pos.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-panis-800 transition {{ request()->routeIs('admin.pos.*') ? 'bg-panis-800 text-gold-400' : '' }}">
                    🛒 Punto de Venta
                </a>
                @endif
                @if(auth()->user()->hasAnyRole(['administrador','gerente','produccion']))
                <a href="{{ route('admin.production.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-panis-800 transition {{ request()->routeIs('admin.production.*') ? 'bg-panis-800 text-gold-400' : '' }}">
                    👨‍🍳 Producción
                </a>
                <a href="{{ route('admin.recipes.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-panis-800 transition {{ request()->routeIs('admin.recipes.*') ? 'bg-panis-800 text-gold-400' : '' }}">
                    📋 Recetas
                </a>
                @endif
                @if(auth()->user()->hasAnyRole(['administrador','gerente']))
                <a href="{{ route('admin.inventory.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-panis-800 transition {{ request()->routeIs('admin.inventory.*') ? 'bg-panis-800 text-gold-400' : '' }}">
                    📦 Inventario
                </a>
                <a href="{{ route('admin.suppliers.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-panis-800 transition {{ request()->routeIs('admin.suppliers.*') ? 'bg-panis-800 text-gold-400' : '' }}">
                    🏭 Proveedores
                </a>
                <a href="{{ route('admin.purchases.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-panis-800 transition {{ request()->routeIs('admin.purchases.*') ? 'bg-panis-800 text-gold-400' : '' }}">
                    🛍️ Compras
                </a>
                <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-panis-800 transition {{ request()->routeIs('admin.reports.*') ? 'bg-panis-800 text-gold-400' : '' }}">
                    📈 Reportes
                </a>
                @endif
            </nav>
            <div class="p-4 border-t border-panis-800">
                <a href="{{ route('home') }}" class="text-sm text-panis-400 hover:text-gold-400 transition">← Volver a la tienda</a>
            </div>
        </aside>

        <div class="flex-1 flex flex-col">
            <header class="bg-white shadow-sm px-6 py-4 flex justify-between items-center">
                <h1 class="font-serif text-xl font-bold text-panis-900">@yield('page-title', 'Dashboard')</h1>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-panis-600">{{ Auth::user()->name }}</span>
                    <span class="badge bg-gold-100 text-gold-700">{{ Auth::user()->getRoleNames()->first() }}</span>
                </div>
            </header>

            @if(session('success'))
                <div class="mx-6 mt-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mx-6 mt-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl">{{ session('error') }}</div>
            @endif

            <main class="flex-1 p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
