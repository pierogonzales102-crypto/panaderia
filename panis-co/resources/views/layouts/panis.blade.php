<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panis & Co') — Panadería Artesanal</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700|playfair-display:400,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased min-h-screen flex flex-col">
    <nav class="bg-panis-gradient text-white sticky top-0 z-50 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    <span class="text-2xl">🥖</span>
                    <span class="font-serif text-xl font-bold tracking-wide group-hover:text-gold-300 transition">Panis & Co</span>
                </a>
                <div class="hidden md:flex items-center gap-6 text-sm font-medium">
                    <a href="{{ route('home') }}" class="hover:text-gold-300 transition">Inicio</a>
                    <a href="{{ route('catalog.index') }}" class="hover:text-gold-300 transition">Catálogo</a>
                    @auth
                        <a href="{{ route('orders.custom') }}" class="hover:text-gold-300 transition">Pedido Personalizado</a>
                        <a href="{{ route('orders.index') }}" class="hover:text-gold-300 transition">Mis Pedidos</a>
                        @if(auth()->user()->isAdmin() || auth()->user()->hasAnyRole(['ventas','produccion','moderador_web']))
                            <a href="{{ route('admin.dashboard') }}" class="hover:text-gold-300 transition">Panel Admin</a>
                        @endif
                    @endauth
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('cart.index') }}" class="relative p-2 hover:text-gold-300 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        @php $cartCount = app(\App\Services\CartService::class)->count(); @endphp
                        @if($cartCount > 0)
                            <span class="absolute -top-1 -right-1 bg-gold-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold">{{ $cartCount }}</span>
                        @endif
                    </a>
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 text-sm hover:text-gold-300 transition">
                                <span class="hidden sm:inline">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                            </button>
                            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl py-2 text-panis-900 z-50">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-panis-50">Mi Perfil</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-panis-50 text-red-600">Cerrar Sesión</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm hover:text-gold-300 transition hidden sm:inline">Iniciar Sesión</a>
                        <a href="{{ route('register') }}" class="btn-primary text-sm !py-2 !px-4">Registrarse</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-4 max-w-7xl mx-auto mt-4 mx-4 rounded-r-xl">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 max-w-7xl mx-auto mt-4 mx-4 rounded-r-xl">
            {{ session('error') }}
        </div>
    @endif

    <main class="flex-1">
        @yield('content')
    </main>

    <footer class="bg-panis-900 text-panis-200 mt-auto">
        <div class="max-w-7xl mx-auto px-4 py-12 grid md:grid-cols-3 gap-8">
            <div>
                <h3 class="font-serif text-xl text-gold-400 mb-3">Panis & Co</h3>
                <p class="text-sm leading-relaxed">Panadería y pastelería artesanal. Panes frescos, pasteles finos y tortas personalizadas hechas con amor.</p>
            </div>
            <div>
                <h4 class="font-semibold text-white mb-3">Enlaces</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('catalog.index') }}" class="hover:text-gold-400 transition">Catálogo</a></li>
                    <li><a href="{{ route('orders.custom') }}" class="hover:text-gold-400 transition">Pedidos Personalizados</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold text-white mb-3">Contacto</h4>
                <p class="text-sm">📍 Lima, Perú</p>
                <p class="text-sm">📞 +51 999 888 777</p>
                <p class="text-sm">✉️ hola@panisandco.com</p>
            </div>
        </div>
        <div class="border-t border-panis-800 text-center py-4 text-sm text-panis-400">
            © {{ date('Y') }} Panis & Co — Todos los derechos reservados
        </div>
    </footer>
</body>
</html>
