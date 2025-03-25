@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <!-- Título centrado -->
    <h2 class="text-primary text-center mb-3">
        👥 Participantes de "{{ $capacitacion->nombre }}"
    </h2>

    <!-- Botones centrados debajo del título -->
    <div class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-3 mb-4 flex-wrap text-center">
        <a href="{{ route('capacitaciones.participantes.create', $capacitacion->id) }}" class="btn btn-primary">➕ Agregar</a>
        <a href="{{ route('participantes.exportar', $capacitacion->id) }}" class="btn btn-success">📤 Exportar</a>
        <form action="{{ route('participantes.importar', $capacitacion->id) }}" method="POST" enctype="multipart/form-data" class="d-flex gap-2 align-items-center flex-wrap justify-content-center">
            @csrf
            <input type="file" name="archivo_excel" id="archivo_excel" class="form-control" required>
            <button type="submit" class="btn btn-secondary">📥 Importar</button>
        </form>
    </div>

    <!-- Mensaje de éxito -->
    @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tabla de participantes -->
    @if ($participantes->count() > 0)
    <div class="table-responsive">
        <table id="tablaParticipantes" class="table table-striped table-bordered align-middle shadow-sm">
            <thead class="table-dark text-center">
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Identidad</th>
                    <th>Edad</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Nivel Educativo</th>
                    <th>Género</th>
                    <th>Empresa / Puesto</th>
                    <th>Ciudad / Municipio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($participantes as $index => $p)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $p->nombre_completo }}</td>
                    <td class="text-center">{{ $p->identidad }}</td>
                    <td class="text-center">{{ $p->edad }}</td>
                    <td>{{ $p->correo }}</td>
                    <td>{{ $p->telefono }}</td>
                    <td class="text-center">{{ $p->nivel_educativo }}</td>
                    <td class="text-center">{{ $p->genero }}</td>
                    <td>{{ $p->empresa ?? '-' }}<br><small>{{ $p->puesto ?? '-' }}</small></td>
                    <td>{{ $p->ciudad }}<br><small>{{ $p->municipio }}</small></td>
                    <td class="text-center">
                        <form action="{{ route('participantes.destroy', $p->id) }}" method="POST" onsubmit="return confirm('¿Eliminar participante?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
        <div class="alert alert-warning text-center">No hay participantes registrados en esta capacitación.</div>
    @endif
</div>

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#tablaParticipantes').DataTable({
            language: {
                lengthMenu: "Mostrar _MENU_ registros",
                zeroRecords: "No se encontraron resultados",
                info: "Página _PAGE_ de _PAGES_",
                infoEmpty: "Sin registros disponibles",
                infoFiltered: "(filtrado de _MAX_ registros)",
                search: "Buscar:",
                paginate: {
                    first: "Primero",
                    last: "Último",
                    next: "→",
                    previous: "←"
                }
            },
            order: [[1, "asc"]],
            columnDefs: [
                { orderable: false, targets: [0, 10] }
            ]
        });
    });
</script>

@if(session('error'))
    <div class="alert alert-danger text-center">
        {{ session('error') }}
    </div>
@endif


<style>
    .table th, .table td {
        vertical-align: middle;
    }

    .btn-danger {
        transition: 0.2s ease;
    }

    .btn-danger:hover {
        transform: scale(1.1);
    }

    .form-control[type="file"] {
        max-width: 220px;
    }

    @media (max-width: 768px) {
        .form-control[type="file"] {
            max-width: 100%;
        }
    }
</style>
@endsection
