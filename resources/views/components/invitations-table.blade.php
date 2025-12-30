@extends('layouts.app')
@section('content')
    <div class="table-wrapper">
        <table class="candidate-table">
            <thead>
                <tr>
                    <th>Rol</th>
                    <th colspan>Email</th>
                    <th>Estado</th>
                    <th>Fecha de envio</th>
                    <th>Fecha de uso</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($invitations as $invitation)
                    <tr>
                        <td>
                            {{ $invitation->collaboratorRole->name }}
                        </td>
                        <td>
                            {{ $invitation->email }}
                        </td>
                        <td
                            class="
                        {{ $invitation->status === 'used' ? 'status-used' : '' }}
                        {{ $invitation->status === 'pending' ? 'status-pending' : '' }}
                        {{ $invitation->status === 'expired' ? 'status-expired' : '' }}
                        ">
                            {{ $invitation->status }}
                        </td>
                        <td>
                            {{ $invitation->created_at }}
                        </td>

                        <td>
                            {{ $invitation->used_at }}
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
            {{ $invitations->links() }}
        </div>
    </div>
@endsection
