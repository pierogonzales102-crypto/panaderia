<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <h2 class="font-serif text-2xl font-bold text-panis-900 text-center mb-6">Iniciar Sesión</h2>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div>
            <x-input-label for="email" value="Correo electrónico" />
            <x-text-input id="email" class="block mt-1 w-full input-field" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="password" value="Contraseña" />
            <x-text-input id="password" class="block mt-1 w-full input-field" type="password" name="password" required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <div class="block mt-4">
            <label class="inline-flex items-center text-sm text-panis-700">
                <input type="checkbox" name="remember" class="rounded text-gold-500">
                <span class="ms-2">Recordarme</span>
            </label>
        </div>
        <div class="flex items-center justify-between mt-6">
            @if (Route::has('password.request'))
                <a class="text-sm text-gold-600 hover:text-gold-700" href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
            @endif
            <button type="submit" class="btn-primary !py-2 !px-6">Ingresar</button>
        </div>
    </form>
    <p class="text-center text-sm text-panis-600 mt-6">¿No tienes cuenta? <a href="{{ route('register') }}" class="text-gold-600 font-medium">Regístrate</a></p>
</x-guest-layout>
