<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4 rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm text-emerald-700" :status="session('status')" />

    <div class="mb-7 text-center">
        <p class="text-sm font-semibold uppercase tracking-wide text-cyan-600">Tu espacio financiero</p>
        <h1 class="mt-2 text-2xl font-bold text-slate-950">Bienvenido de nuevo</h1>
        <p class="mt-2 text-sm leading-6 text-slate-500">
            Entra a Aniafi y sigue organizando tus finanzas con calma.
        </p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5" novalidate>
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Correo electrónico')" class="text-slate-700" />
            <x-text-input id="email"
                class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 shadow-none transition placeholder:text-slate-400 focus:border-cyan-500 focus:bg-white focus:ring-cyan-500"
                type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                placeholder="tu@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Contraseña')" class="text-slate-700" />

            <x-text-input id="password"
                class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 shadow-none transition placeholder:text-slate-400 focus:border-cyan-500 focus:bg-white focus:ring-cyan-500"
                type="password" name="password" required autocomplete="current-password" placeholder="Tu contraseña" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between gap-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-slate-300 text-cyan-600 shadow-sm focus:ring-cyan-500" name="remember">
                <span class="ms-2 text-sm text-slate-600">{{ __('Recordarme') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-medium text-blue-700 transition hover:text-cyan-600 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2"
                    href="{{ route('password.request') }}">
                    {{ __('Olvidé mi contraseña') }}
                </a>
            @endif
        </div>

        <x-primary-button
            class="flex w-full justify-center rounded-2xl bg-gradient-to-r from-blue-700 via-cyan-600 to-emerald-500 px-5 py-3 text-sm normal-case tracking-normal shadow-lg shadow-cyan-700/20 transition hover:from-blue-800 hover:via-cyan-700 hover:to-emerald-600 focus:ring-cyan-500">
            {{ __('Iniciar sesión') }}
        </x-primary-button>

        <div class="border-t border-slate-100 pt-5 text-center">
            @if (Route::has('register'))
                <span class="text-sm text-slate-500">{{ __('¿Aún no tienes cuenta?') }}</span>
                <a class="ms-1 text-sm font-semibold text-blue-700 transition hover:text-cyan-600 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2"
                    href="{{ route('register') }}">
                    {{ __('Crear cuenta') }}
                </a>
            @endif
        </div>
    </form>
</x-guest-layout>
