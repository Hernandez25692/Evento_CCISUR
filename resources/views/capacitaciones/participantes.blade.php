@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="text-center mb-4">Participantes de {{ $capacitacion->nombre }}</h1>

    <a href="{{ route('capacitaciones.participantes.create', $capacitacion->id) }}" class="btn btn-primary mb-3">➕ Agregar Participante</a>
    
    @if ($participantes->count() > 0)
    <div class="table-responsive">
        <table id="tablaParticipantes" class="table table-hover table-bordered shadow-sm">
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

<!-- Agregar DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#tablaParticipantes').DataTable({
            "language": {
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "zeroRecords": "No se encontraron resultados",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoEmpty": "No hay registros disponibles",
                "infoFiltered": "(filtrado de _MAX_ registros en total)",
                "search": "Buscar:",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            "order": [[1, "asc"]], // Ordenar por nombre (ascendente)
            "columnDefs": [
                { "orderable": false, "targets": [0, 10] } // Desactivar orden en # y Acciones
            ]
        });
    });
</script>

<style>
    .table {
        border-radius: 10px;
        overflow: hidden;
    }

    .table thead {
        background-color: #343a40;
        color: white;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
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
