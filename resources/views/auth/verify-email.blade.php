<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 space-y-2">
        <p>
            Gracias por registrarte en <strong>Aniafi</strong>.
        </p>

        <p>
            Tu cuenta ya está casi lista. Para comenzar, confirma tu correo electrónico desde el enlace que te enviamos.
        </p>

        <p>
            Revisa tu bandeja de entrada o la carpeta de spam.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ __('Un nuevo enlace de verificación fue enviado') }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('Reenviar Email') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit"
                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('Salir') }}
            </button>
        </form>
    </div>
</x-guest-layout>
