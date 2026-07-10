<x-guest-layout>
    <div class="text-center">
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>

        <p class="mt-5 text-sm font-semibold uppercase tracking-wide text-cyan-600">Cuenta verificada</p>
        <h1 class="mt-2 text-2xl font-bold text-slate-950">Tu correo fue confirmado</h1>
        <p class="mt-3 text-sm leading-6 text-slate-500">
            Ya puedes entrar a Aniafi y continuar organizando tus finanzas.
        </p>

        <a href="{{ route('dashboard') }}"
            class="mt-7 inline-flex w-full justify-center rounded-2xl bg-gradient-to-r from-blue-700 via-cyan-600 to-emerald-500 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-cyan-700/20 transition hover:from-blue-800 hover:via-cyan-700 hover:to-emerald-600 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2">
            Ir al panel
        </a>
    </div>
</x-guest-layout>
