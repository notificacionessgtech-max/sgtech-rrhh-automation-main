<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-2">
                    <h1 class="text-2xl font-semibold text-gray-800">
                        ¡Hola, {{ Auth::user()->name }}!
                    </h1>

                    <p class="text-gray-700">
                        Bienvenido/a al panel de RRHH. Has iniciado sesión como
                        <span class="font-medium text-blue-600">
                            @if (Auth::user()->getRoleNames()->first() === 'rrhh')
                                Recursos Humanos
                            @else
                                {{ Auth::user()->getRoleNames()->first() }}
                            @endif
                        </span>
                    </p>

                    <p class="text-sm text-gray-600">
                        Desde aquí puedes gestionar colaboradores, descargar documentos, enviar invitaciones y más.
                    </p>

                    <div class="mt-4">
                        <a href="{{ route('registered.users') }}"
                            class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-md shadow transition">
                            Ver fichas de contratación
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
