@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="text-center mb-4">Participantes de {{ $capacitacion->nombre }}</h1>

    <a href="{{ route('capacitaciones.participantes.create', $capacitacion->id) }}" class="btn btn-primary mb-3">➕ Agregar Participante</a>
    
    @if ($participantes->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover table-bordered shadow-sm">
            <thead class="table-dark text-center">
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Empresa</th>
                    <th>Puesto</th>
                    <th>Edad</th>
                    <th>Identidad</th>
                    <th>Nivel Educativo</th>
                    <th>Género</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($participantes as $index => $participante)
                <tr class="align-middle">
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $participante->nombre_completo }}</td>
                    <td>{{ $participante->correo }}</td>
                    <td>{{ $participante->telefono }}</td>
                    <td>{{ $participante->empresa ?? '-' }}</td>
                    <td>{{ $participante->puesto ?? '-' }}</td>
                    <td class="text-center">{{ $participante->edad }}</td>
                    <td class="text-center">{{ $participante->identidad }}</td>
                    <td class="text-center">{{ $participante->nivel_educativo }}</td>
                    <td class="text-center">{{ $participante->genero }}</td>
                    <td class="text-center">
                        <form action="{{ route('participantes.destroy', $participante->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">🗑️ Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <p class="text-center alert alert-warning">No hay participantes registrados en esta capacitación.</p>
    @endif
</div>

<style>
    .table {
        border-radius: 10px;
        overflow: hidden;
    }

    .table thead {
        background-color: #007bff;
        color: white;
    }

    .table-hover tbody tr:hover {
        background-color: #f1f1f1;
    }

    .btn-danger {
        transition: all 0.3s ease;
    }

    .btn-danger:hover {
        background-color: #dc3545;
        transform: scale(1.1);
    }
</style>

@endsection
