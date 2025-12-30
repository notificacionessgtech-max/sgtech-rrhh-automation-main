{{-- resources/views/dashboard.blade.php --}}
<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Lista de Usuarios
        </h2>
    </x-slot> --}}

    <div class="max-w-md mx-auto mt-10 bg-white p-6 rounded-lg shadow">
        <form method="POST" action="{{ route('send.welcome.email') }}" id="emailForm" class="space-y-4">
            @csrf

            <input type="email" name="email" placeholder="Email" required
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">

            <select name="role" required
                class="w-full px-4 py-2 border border-gray-300 rounded-md bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @forelse ($collaboratorRoles as $roles)
                    <option value="{{ $roles->collaborator_role_id }}">
                        {{ $roles->name }}
                    </option>
                @empty
                    <option disabled selected>No hay roles disponibles</option>
                @endforelse
            </select>

            <button type="submit"
                class="w-full flex justify-center items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md shadow transition disabled:opacity-50">
                Enviar
                <span id="spinner"
                    class="hidden w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
            </button>
        </form>
    </div>

</x-app-layout>
