@extends('layouts.app')
@section('content')
    <div class="table-wrapper">
        <table class="candidate-table">
            <thead>
                <tr>
                    <th colspan="2">Nombre</th>
                    <th>Rol</th>
                    <th>Posicion</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td colspan="2">
                            {{ $user->first_name }}
                            {{ $user->last_name }}
                        </td>
                        <td>{{ $user->role }}</td>
                        <td>{{ $user->job_position }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone_number }}</td>
                        <td colspan="2">
                            <button class="btn-detail" data-id="{{ $user->personal_data_id }}">
                                Detalles
                            </button>

                            @if ($user->uploadedDocuments->isNotEmpty())
                                <a href="{{ route('employees.download.all', $user->personal_data_id) }}" class="btn-document">
                                    Descargar documentos
                                </a>
                            @else
                            @endif

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td>
                            <span>No hay datos</span>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="pagination-wrapper">
            {{ $users->links() }}
        </div>
    </div>
    {{-- modal --}}
    <div class="modal hidden" id="userDetailModal">
        <div class="modal-content">
            <button class="modal-close">&times;</button>
            <h2>Detalle del usuario</h2>
            <div id="user-detail-content">
                <!-- Cargarás la información por AJAX o la inyectarás en el DOM -->
            </div>
        </div>
    </div>
@endsection
