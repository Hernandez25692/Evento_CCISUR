@extends('layouts.app')

@section('content')
    <div class="participant-filter-container py-4">
        <!-- Encabezado -->
        <div class="filter-header text-center mb-4">
            <h2 class="fw-bold text-primary mb-2">
                <i class="fas fa-filter me-2"></i>Filtro de Participantes
            </h2>
            <p class="text-muted">Filtre los participantes según diferentes criterios</p>
        </div>

        <!-- Panel de Filtros -->
        <div class="card filter-card shadow-sm mb-4">
            <div class="card-body p-3">
                <form method="GET" action="{{ route('dashboard.filtro') }}">
                    <div class="row g-3">
                        <!-- Filtro por Edad -->
                        <div class="col-md-3">
                            <label class="form-label small text-muted mb-1"><i
                                    class="fas fa-user-clock me-1"></i>Edad</label>
                            <input type="number" name="edad" class="form-control form-control-sm" placeholder="Ej: 25"
                                value="{{ request('edad') }}">
                        </div>

                        <!-- Filtro por Género -->
                        <div class="col-md-3">
                            <label class="form-label small text-muted mb-1"><i
                                    class="fas fa-venus-mars me-1"></i>Género</label>
                            <select name="genero" class="form-select form-select-sm">
                                <option value="">Todos</option>
                                <option value="Masculino" {{ request('genero') == 'Masculino' ? 'selected' : '' }}>Masculino
                                </option>
                                <option value="Femenino" {{ request('genero') == 'Femenino' ? 'selected' : '' }}>Femenino
                                </option>
                                <option value="Otro" {{ request('genero') == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>

                        <!-- Filtro por Nivel Educativo -->
                        <div class="col-md-3">
                            <label class="form-label small text-muted mb-1">
                                <i class="fas fa-graduation-cap me-1"></i>Educación
                            </label>
                            <select name="nivel_educativo" class="form-select form-select-sm">
                                <option value="">Todos</option>
                                <option value="Primaria Completa"
                                    {{ request('nivel_educativo') == 'Primaria Completa' ? 'selected' : '' }}>Primaria
                                    Completa</option>
                                <option value="Primaria Incompleta"
                                    {{ request('nivel_educativo') == 'Primaria Incompleta' ? 'selected' : '' }}>Primaria
                                    Incompleta</option>
                                <option value="Secundaria Completa"
                                    {{ request('nivel_educativo') == 'Secundaria Completa' ? 'selected' : '' }}>Secundaria
                                    Completa</option>
                                <option value="Secundaria Incompleta"
                                    {{ request('nivel_educativo') == 'Secundaria Incompleta' ? 'selected' : '' }}>Secundaria
                                    Incompleta</option>
                                <option value="Técnico Completo"
                                    {{ request('nivel_educativo') == 'Técnico Completo' ? 'selected' : '' }}>Técnico
                                    Completo</option>
                                <option value="Técnico Incompleto"
                                    {{ request('nivel_educativo') == 'Técnico Incompleto' ? 'selected' : '' }}>Técnico
                                    Incompleto</option>
                                <option value="Universitaria Completa"
                                    {{ request('nivel_educativo') == 'Universitaria Completa' ? 'selected' : '' }}>
                                    Universitaria Completa</option>
                                <option value="Universitaria Incompleta"
                                    {{ request('nivel_educativo') == 'Universitaria Incompleta' ? 'selected' : '' }}>
                                    Universitaria Incompleta</option>
                            </select>
                        </div>


                        <!-- Filtro por Empresa -->
                        <div class="col-md-3">
                            <label class="form-label small text-muted mb-1"><i
                                    class="fas fa-building me-1"></i>Empresa</label>
                            <input type="text" name="empresa" class="form-control form-control-sm"
                                placeholder="Nombre de empresa" value="{{ request('empresa') }}">
                        </div>

                        <!-- Filtro por Municipio -->
                        <div class="col-md-3">
                            <label class="form-label small text-muted mb-1"><i
                                    class="fas fa-map-marker-alt me-1"></i>Municipio</label>
                            <input type="text" name="municipio" class="form-control form-control-sm"
                                placeholder="Ej: Central" value="{{ request('municipio') }}">
                        </div>

                        <!-- Botones de Acción -->
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="fas fa-filter me-1"></i>Filtrar
                            </button>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <a href="{{ route('dashboard.filtro') }}" class="btn btn-outline-secondary btn-sm w-100">
                                <i class="fas fa-eraser me-1"></i>Limpiar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Resultados -->
        @if ($participantes->count() > 0)
            <div class="card results-card shadow-sm">
                <div class="card-header bg-white py-2 px-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="m-0">
                            <i class="fas fa-list-check me-2"></i>Resultados
                        </h5>
                        <span class="badge bg-primary rounded-pill">{{ $participantes->total() }}</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="50" class="text-center">#</th>
                                    <th>Participante</th>
                                    <th>Contacto</th>
                                    <th>Empresa</th>
                                    <th width="80" class="text-center">Edad</th>
                                    <th>Educación</th>
                                    <th width="100" class="text-center">Género</th>
                                    <th>Municipio</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($participantes as $participante)
                                    <tr>
                                        <td class="text-center fw-bold text-muted">
                                            {{ ($participantes->currentPage() - 1) * $participantes->perPage() + $loop->iteration }}
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="avatar bg-primary bg-opacity-10 text-primary rounded-circle me-2">
                                                    {{ substr($participante->nombre_completo, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $participante->nombre_completo }}</div>
                                                    <div class="text-muted small">{{ $participante->correo }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="small">{{ $participante->telefono }}</td>
                                        <td>{{ $participante->empresa ?? '-' }}</td>
                                        <td class="text-center">{{ $participante->edad }}</td>
                                        <td class="small">{{ $participante->nivel_educativo }}</td>
                                        <td class="text-center">
                                            <span
                                                class="badge rounded-pill bg-{{ $participante->genero == 'Femenino' ? 'success' : 'primary' }} bg-opacity-10 text-{{ $participante->genero == 'Femenino' ? 'success' : 'primary' }}">
                                                {{ $participante->genero }}
                                            </span>
                                        </td>
                                        <td class="small">{{ $participante->municipio }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white py-2 px-3">
                    <div class="d-flex justify-content-center">
                        {{ $participantes->withQueryString()->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        @else
            <div class="card empty-state border-0 shadow-sm">
                <div class="card-body text-center py-4">
                    <div class="empty-icon mb-3">
                        <i class="fas fa-search-minus fa-2x text-muted"></i>
                    </div>
                    <h5 class="empty-title fw-bold mb-2">No se encontraron resultados</h5>
                    <p class="empty-text text-muted small mb-3">Pruebe ajustando los parámetros de búsqueda</p>
                    <a href="{{ route('dashboard.filtro') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eraser me-1"></i>Limpiar filtros
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- CDNs -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        .participant-filter-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .filter-header {
            padding: 1rem;
            background-color: #f8f9fa;
            border-radius: 0.5rem;
        }

        .filter-card {
            border: none;
            border-radius: 0.5rem;
        }

        .form-control-sm,
        .form-select-sm {
            font-size: 0.875rem;
            padding: 0.25rem 0.5rem;
            height: calc(1.8rem + 2px);
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .results-card {
            border: none;
            border-radius: 0.5rem;
        }

        .results-card .table {
            font-size: 0.875rem;
            margin-bottom: 0;
        }

        .results-card .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: #6c757d;
            padding: 0.5rem 0.75rem;
        }

        .results-card .table td {
            padding: 0.5rem 0.75rem;
            vertical-align: middle;
        }

        .results-card .table tr:hover td {
            background-color: rgba(67, 97, 238, 0.03);
        }

        .avatar {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .badge {
            font-weight: 500;
            padding: 0.35em 0.5em;
        }

        .empty-state {
            border-radius: 0.5rem;
        }

        .empty-icon {
            opacity: 0.6;
        }

        .pagination .page-link {
            font-size: 0.875rem;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem !important;
            margin: 0 0.1rem;
            border: 1px solid #dee2e6;
        }

        @media (max-width: 768px) {
            .filter-header h2 {
                font-size: 1.5rem;
            }

            .results-card .table-responsive {
                border-radius: 0.5rem;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
        }
    </style>
@endsection
