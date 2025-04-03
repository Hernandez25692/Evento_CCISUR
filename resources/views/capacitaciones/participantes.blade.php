@extends('layouts.app')

@section('content')
<div class="participants-dashboard">
    <!-- Header -->
    <header class="dashboard-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="dashboard-title">
                        <i class="fas fa-users me-2"></i>Participantes
                    </h1>
                    <p class="dashboard-subtitle">Gestión de participantes para "{{ $capacitacion->nombre }}"</p>
                </div>
                <div class="dashboard-breadcrumb">
                    <a href="{{ route('capacitaciones.index') }}">Formaciones</a>
                    <span>/</span>
                    <span>Participantes</span>
                </div>
            </div>
        </div>
    </header>

    <div class="container py-4">
        <!-- Alertas -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Panel de Acciones -->
        <div class="card action-panel mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <a href="{{ route('capacitaciones.participantes.create', $capacitacion->id) }}" class="btn btn-primary w-100">
                            <i class="fas fa-user-plus me-2"></i>Agregar Participante
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('participantes.exportar', $capacitacion->id) }}" class="btn btn-success w-100">
                            <i class="fas fa-file-excel me-2"></i>Exportar Excel
                        </a>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-secondary w-100" data-bs-toggle="modal" data-bs-target="#importModal">
                            <i class="fas fa-file-import me-2"></i>Importar Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Importación -->
        <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel">
                            <i class="fas fa-file-import me-2"></i>Importar Participantes
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('participantes.importar', $capacitacion->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="archivo_excel" class="form-label">Seleccione archivo Excel</label>
                                <input type="file" class="form-control" id="archivo_excel" name="archivo_excel" required accept=".xlsx,.xls">
                                <div class="form-text">Formatos aceptados: .xlsx, .xls (máx. 5MB)</div>
                            </div>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Descargue la <a href="{{ asset('plantillas/plantilla_participantes.xlsx') }}" class="alert-link">plantilla de ejemplo</a> para asegurar el formato correcto.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Importar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Resumen -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card stat-card stat-primary">
                    <div class="card-body">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label">Total Participantes</span>
                            <h3 class="stat-value">{{ $participantes->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card stat-success">
                    <div class="card-body">
                        <div class="stat-icon">
                            <i class="fas fa-venus"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label">Mujeres</span>
                            <h3 class="stat-value">{{ $participantes->where('genero', 'Femenino')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card stat-warning">
                    <div class="card-body">
                        <div class="stat-icon">
                            <i class="fas fa-mars"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label">Hombres</span>
                            <h3 class="stat-value">{{ $participantes->where('genero', 'Masculino')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card stat-info">
                    <div class="card-body">
                        <div class="stat-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label">Empresas</span>
                            <h3 class="stat-value">{{ $participantes->unique('empresa')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Participantes -->
        @if ($participantes->count() > 0)
        <div class="card table-card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list-alt me-2"></i>Listado de Participantes
                    </h5>
                    <div class="table-actions">
                        <div class="input-group" style="max-width: 250px;">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" id="searchInput" class="form-control" placeholder="Buscar...">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="participantsTable" class="table table-hover align-middle" style="width:100%">
                       <thead>
    <tr>
        <th>#</th>
        <th>Nombre</th>
        <th>Identidad</th>
        <th>Edad</th>
        <th>Contacto</th>
        <th>Educación</th>
        <th>Género</th>
        <th>Empresa</th>
        <th>Ubicación</th>
        @if(strtolower($capacitacion->medio ?? '') == 'pago')
            <th>Precio</th>
            <th>ISV</th>
            <th>Total</th>
            <th>Comprobante</th>
        @endif
        <th>Acciones</th>
    </tr>
</thead>
<tbody>
    @foreach ($participantes as $p)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>
            <div class="participant-info">
                <div class="participant-name">{{ $p->nombre_completo }}</div>
                <div class="participant-email text-muted small">{{ $p->correo }}</div>
            </div>
        </td>
        <td>{{ $p->identidad }}</td>
        <td>{{ $p->edad }}</td>
        <td>
            <div class="contact-info">
                <div><i class="fas fa-phone me-2"></i>{{ $p->telefono }}</div>
            </div>
        </td>
        <td>{{ $p->nivel_educativo }}</td>
        <td>
            <span class="badge bg-{{ $p->genero == 'Femenino' ? 'success' : 'primary' }}">
                {{ $p->genero }}
            </span>
        </td>
        <td>
            <div class="company-info">
                <div class="company-name">{{ $p->empresa ?? '-' }}</div>
                <div class="company-position small text-muted">{{ $p->puesto ?? '-' }}</div>
            </div>
        </td>
        <td>
            <div class="location-info">
                <div>{{ $p->ciudad }}</div>
                <div class="small text-muted">{{ $p->municipio }}</div>
            </div>
        </td>
        @if(strtolower($capacitacion->medio ?? '') == 'pago')
        <td>{{ number_format($p->precio ?? 0, 2) }}</td>
        <td>{{ number_format($p->isv ?? 0, 2) }}</td>
        <td>{{ number_format($p->total ?? 0, 2) }}</td>
        <td>
            @if($p->comprobante)
                <a href="{{ asset('storage/' . $p->comprobante) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    Ver
                </a>
            @else
                <span class="text-muted">No adjunto</span>
            @endif
        </td>
        @endif
        <td>
            <div class="d-flex gap-2">
                <form action="{{ route('participantes.destroy', $p->id) }}" method="POST" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Eliminar">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            </div>
        </td>
    </tr>
    @endforeach
</tbody>

                    </table>
                </div>
            </div>
        </div>
        @else
        <div class="card empty-state">
            <div class="card-body text-center py-5">
                <div class="empty-icon mb-4">
                    <i class="fas fa-users-slash"></i>
                </div>
                <h5 class="empty-title">No hay participantes registrados</h5>
                <p class="empty-text">Agregue nuevos participantes para comenzar</p>
                <a href="{{ route('capacitaciones.participantes.create', $capacitacion->id) }}" class="btn btn-primary">
                    <i class="fas fa-user-plus me-2"></i>Agregar Primer Participante
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- CDNs -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --success-color: #28a745;
        --warning-color: #fd7e14;
        --info-color: #17a2b8;
        --light-color: #f8f9fa;
        --dark-color: #212529;
    }

    body {
        background-color: #f5f7fb;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .dashboard-header {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 1.5rem 0;
        margin-bottom: 1.5rem;
    }

    .dashboard-title {
        font-weight: 700;
        font-size: 1.8rem;
        margin-bottom: 0.25rem;
    }

    .dashboard-subtitle {
        font-weight: 300;
        opacity: 0.9;
        font-size: 1rem;
    }

    .dashboard-breadcrumb {
        font-size: 0.9rem;
    }

    .dashboard-breadcrumb a {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
    }

    .dashboard-breadcrumb a:hover {
        color: white;
        text-decoration: underline;
    }

    .dashboard-breadcrumb span {
        color: rgba(255, 255, 255, 0.6);
    }

    .action-panel {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .action-panel .card-body {
        padding: 1.5rem;
    }

    .stat-card {
        border: none;
        border-radius: 10px;
        overflow: hidden;
        height: 100%;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .stat-primary {
        border-left: 4px solid var(--primary-color);
    }

    .stat-success {
        border-left: 4px solid var(--success-color);
    }

    .stat-warning {
        border-left: 4px solid var(--warning-color);
    }

    .stat-info {
        border-left: 4px solid var(--info-color);
    }

    .stat-icon {
        font-size: 1.5rem;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }

    .stat-success .stat-icon {
        color: var(--success-color);
    }

    .stat-warning .stat-icon {
        color: var(--warning-color);
    }

    .stat-info .stat-icon {
        color: var(--info-color);
    }

    .stat-label {
        font-size: 0.9rem;
        color: #6c757d;
        font-weight: 500;
    }

    .stat-value {
        font-weight: 700;
        color: var(--dark-color);
        margin: 0.25rem 0;
        font-size: 1.5rem;
    }

    .table-card {
        border: none;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .table-card .card-header {
        background-color: white;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1.25rem 1.5rem;
    }

    .table-card .card-title {
        font-weight: 600;
        color: var(--dark-color);
        margin: 0;
        font-size: 1.1rem;
    }

    .table-card .card-body {
        padding: 0;
    }

    #participantsTable {
        width: 100% !important;
    }

    #participantsTable thead th {
        background-color: #f8f9fa;
        color: #495057;
        font-weight: 600;
        padding: 1rem 1.25rem;
        border-bottom-width: 1px;
    }

    #participantsTable tbody td {
        padding: 0.75rem 1.25rem;
        vertical-align: middle;
    }

    .participant-info .participant-name {
        font-weight: 500;
    }

    .participant-info .participant-email {
        font-size: 0.85rem;
    }

    .company-info .company-name {
        font-weight: 500;
    }

    .company-info .company-position {
        font-size: 0.85rem;
    }

    .location-info {
        font-size: 0.9rem;
    }

    .contact-info {
        font-size: 0.9rem;
    }

    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
        font-size: 0.85em;
    }

    .empty-state {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .empty-state .card-body {
        padding: 3rem;
    }

    .empty-icon {
        font-size: 3rem;
        color: #adb5bd;
    }

    .empty-title {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }

    .empty-text {
        color: #6c757d;
        margin-bottom: 1.5rem;
    }

    .btn {
        padding: 0.5rem 1.25rem;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
    }

    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-primary:hover {
        background-color: var(--secondary-color);
        border-color: var(--secondary-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .delete-form .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .delete-form .btn-danger:hover {
        background-color: #bb2d3b;
        border-color: #bb2d3b;
        transform: scale(1.05);
    }

    @media (max-width: 768px) {
        .dashboard-title {
            font-size: 1.5rem;
        }
        
        .stat-card {
            margin-bottom: 1rem;
        }

        .table-actions {
            margin-top: 1rem;
            width: 100%;
        }

        .table-actions .input-group {
            max-width: 100% !important;
        }
    }
</style>

<script>
    $(document).ready(function() {
        // Inicializar DataTable
        var table = $('#participantsTable').DataTable({
            language: {
                lengthMenu: "Mostrar _MENU_ registros por página",
                zeroRecords: "No se encontraron participantes",
                info: "Mostrando _END_ de _TOTAL_ participantes",
                infoEmpty: "No hay participantes disponibles",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                search: "Buscar:",
                paginate: {
                    first: "Primero",
                    last: "Último",
                    next: "Siguiente",
                    previous: "Anterior"
                }
            },
            responsive: true,
            dom: '<"top"f>rt<"bottom"lip><"clear">',
            pageLength: 10,
            columnDefs: [
                { orderable: false, targets: [0, 9] }
            ],
            order: [[1, 'asc']]
        });

        // Búsqueda personalizada
        $('#searchInput').keyup(function() {
            table.search($(this).val()).draw();
        });

        // Tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Confirmación de eliminación
        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: '¿Eliminar participante?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
</script>
@endsection