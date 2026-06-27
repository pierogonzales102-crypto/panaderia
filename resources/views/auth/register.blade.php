<x-guest-layout>
    <h2 class="font-serif text-2xl font-bold text-panis-900 text-center mb-6">Crear Cuenta</h2>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div>
            <x-input-label for="name" value="Nombre completo" />
            <x-text-input id="name" class="block mt-1 w-full input-field" type="text" name="name" :value="old('name')" required autofocus />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="email" value="Correo electrónico" />
            <x-text-input id="email" class="block mt-1 w-full input-field" type="email" name="email" :value="old('email')" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="phone" value="Teléfono" />
            <x-text-input id="phone" class="block mt-1 w-full input-field" type="text" name="phone" :value="old('phone')" placeholder="+51 999 888 777" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="password" value="Contraseña" />
            <x-text-input id="password" class="block mt-1 w-full input-field" type="password" name="password" required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Confirmar contraseña" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full input-field" type="password" name="password_confirmation" required />
        </div>
        <div class="mt-4">
            <label class="flex items-start gap-2 text-sm text-panis-700">
                <input type="checkbox" name="terms" value="1" class="rounded text-gold-500 mt-1" required>
                Acepto los términos y condiciones del servicio
            </label>
            <x-input-error :messages="$errors->get('terms')" class="mt-2" />
        </div>
        <div class="flex items-center justify-between mt-6">
            <a class="text-sm text-gold-600 hover:text-gold-700" href="{{ route('login') }}">¿Ya tienes cuenta?</a>
            <button type="submit" class="btn-primary !py-2 !px-6">Registrarse</button>
        </div>
    </form>
</x-guest-layout>
