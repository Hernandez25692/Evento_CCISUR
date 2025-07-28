@extends('layouts.app')

@section('content')
    <div class="training-list-container">
        <!-- Encabezado con breadcrumbs -->
        <div class="page-header">
            <div class="header-content">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Formaciones</li>
                    </ol>
                </nav>
                <h1>
                    <i class="fas fa-book-open"></i>
                    Lista de Formaciones
                </h1>
                <p class="subtitle">Gestiona todas tus actividades de capacitación</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('capacitaciones.create') }}" class="create-btn">
                    <i class="fas fa-plus-circle"></i> Nueva Formación
                </a>
            </div>
        </div>

        <!-- Buscador avanzado -->
        <div class="advanced-search-container">
            <form method="GET" action="{{ route('capacitaciones.index') }}" id="searchForm">
                <div class="search-card">
                    <div class="search-header">
                        <i class="fas fa-sliders-h"></i>
                        <h4>Búsqueda Avanzada</h4>
                        <button type="button" class="toggle-filters" id="toggleFilters">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>

                    <div class="search-body" id="searchBody">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="search-group">
                                    <i class="fas fa-search"></i>
                                    <input type="text" name="buscar" class="form-control search-input"
                                        placeholder="Buscar formación por nombre" value="{{ request('buscar') }}">
                                </div>
                            </div>

                            <div class="col-md-4 filter-group">
                                <label><i class="fas fa-filter"></i> Tipo de Formación</label>
                                <select name="tipo" class="form-select">
                                    <option value="">Todos los tipos</option>
                                    @foreach ($tipo_formacion as $tipo)
                                        <option value="{{ $tipo }}"
                                            {{ request('tipo') == $tipo ? 'selected' : '' }}>
                                            {{ $tipo }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 filter-group">
                                <label><i class="fas fa-calendar-alt"></i> Rango de Fechas</label>
                                <div class="date-range">
                                    <input type="date" name="desde" class="form-control" value="{{ request('desde') }}"
                                        placeholder="Desde">
                                    <span>a</span>
                                    <input type="date" name="hasta" class="form-control" value="{{ request('hasta') }}"
                                        placeholder="Hasta">
                                </div>
                            </div>

                            <div class="col-md-4 filter-group">
                                <label><i class="fas fa-broadcast-tower"></i> Modalidad</label>
                                <select name="modalidad" class="form-select">
                                    <option value="">Todas las modalidades</option>
                                    <option value="presencial" {{ request('modalidad') == 'presencial' ? 'selected' : '' }}>
                                        Presencial</option>
                                    <option value="virtual" {{ request('modalidad') == 'virtual' ? 'selected' : '' }}>
                                        Virtual</option>
                                    <option value="hibrida" {{ request('modalidad') == 'hibrida' ? 'selected' : '' }}>
                                        Híbrida</option>
                                </select>
                            </div>

                            <div class="col-12 filter-actions">
                                <button type="submit" class="btn btn-primary search-btn">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Contador de resultados -->
        <div class="results-info">
            <span class="badge bg-primary">{{ $capacitaciones->total() }}</span>
            {{ $capacitaciones->total() == 1 ? 'formación encontrada' : 'formaciones encontradas' }}

            @if (request()->hasAny(['buscar', 'tipo', 'desde', 'hasta', 'modalidad']))
                <a href="{{ route('capacitaciones.index') }}" class="clear-filters">
                    <i class="fas fa-times"></i> Limpiar filtros
                </a>
            @endif
        </div>

        <!-- Contenido principal -->
        @if ($capacitaciones->count() > 0)
            <div class="training-grid">
                @foreach ($capacitaciones as $capacitacion)
                    <div class="training-card">
                        <!-- Imagen con badge de estado -->
                        <div class="card-image">
                            <a href="{{ route('capacitaciones.participantes', $capacitacion->id) }}">
                                @if ($capacitacion->imagen)
                                    <img src="{{ asset('storage/' . $capacitacion->imagen) }}" alt="Imagen de la capacitación">
                                @else
                                    <img src="{{ asset('images/default-training.jpg') }}" alt="Imagen por defecto">
                                @endif
                            </a>
                            <div class="image-overlay"></div>
                            <div class="card-badge {{ $capacitacion->fecha < now() ? 'past' : 'upcoming' }}">
                                {{ $capacitacion->fecha < now() ? 'Realizada' : 'Próxima' }}
                            </div>
                        </div>

                        <!-- Contenido de la tarjeta -->
                        <div class="card-content">
                            <div class="card-header">
                                <h3>{{ $capacitacion->nombre }}</h3>
                                <span class="card-type">{{ $capacitacion->tipo_formacion }}</span>
                                <span class="card-type">{{ $capacitacion->medio }}</span>
                                <div style="margin-top: 0.5rem; font-size: 0.95em; color: var(--text-light);">
                                    <i class="fas fa-clock"></i>
                                    {{ $capacitacion->hora_inicio }} - {{ $capacitacion->hora_fin }}
                                </div>
                            </div>

                            <div class="card-details">
                                <div class="detail-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>{{ $capacitacion->lugar }}</span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>{{ \Carbon\Carbon::parse($capacitacion->fecha)->isoFormat('D [de] MMMM [de] YYYY') }}</span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-users"></i>
                                    <span>{{ $capacitacion->participantes_count }} participantes</span>
                                </div>
                            </div>

                            <!-- Barra de progreso para cupos -->
                            @if ($capacitacion->cupos == 'limitado' && $capacitacion->limite_participantes)
                                <div class="progress-container">
                                    <div class="progress-info">
                                        <span>Cupos disponibles</span>
                                        <span>{{ $capacitacion->limite_participantes - $capacitacion->participantes_count }}
                                            / {{ $capacitacion->limite_participantes }}</span>
                                    </div>
                                    <div class="progress">
                                        @php
                                            $percentage =
                                                ($capacitacion->participantes_count /
                                                    $capacitacion->limite_participantes) *
                                                100;
                                        @endphp
                                        <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%"
                                            aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Botón de acciones y modal de detalles -->
                            <div class="card-actions">
                                <!-- Botón que abre el modal --

                                                <!-- Dropdown de acciones -->
                                <div class="dropdown">
                                    <button class="btn btn-primary btn-sm w-100 mb-2" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-cogs"></i> Opciones
                                    </button>
                                    <ul class="dropdown-menu w-100">
                                        <li><a class="dropdown-item"
                                                href="{{ route('capacitaciones.edit', $capacitacion->id) }}"><i
                                                    class="fas fa-edit me-2"></i>Editar</a></li>
                                        <li><a class="dropdown-item"
                                                href="{{ route('capacitaciones.participantes', $capacitacion->id) }}"><i
                                                    class="fas fa-users me-2"></i>Participantes</a></li>
                                        <li><a class="dropdown-item"
                                                href="{{ route('capacitaciones.participantes.create', $capacitacion->id) }}"><i
                                                    class="fas fa-user-plus me-2"></i>Agregar participante</a></li>
                                        <li><a class="dropdown-item"
                                                href="{{ route('capacitaciones.plantilla', $capacitacion->id) }}"><i
                                                    class="fas fa-file-alt me-2"></i>Cargar Plantilla/Diploma</a></li>
                                        <li><a class="dropdown-item"
                                                href="{{ route('capacitaciones.diplomas', $capacitacion->id) }}"><i
                                                    class="fas fa-certificate me-2"></i>Generar diplomas</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <button class="dropdown-item text-danger"
                                                onclick="confirmarEliminacion({{ $capacitacion->id }})">
                                                <i class="fas fa-trash-alt me-2"></i>Eliminar
                                            </button>
                                            <form id="eliminar-capacitacion-{{ $capacitacion->id }}"
                                                action="{{ route('capacitaciones.destroy', $capacitacion->id) }}"
                                                method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>


                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginación mejorada -->
            <div class="custom-pagination">
                {{ $capacitaciones->appends(request()->query())->links() }}
            </div>
        @else
            <div class="no-results">
                <img src="{{ asset('storage/logo/Logo-CCISUR.png') }}" alt="Sin resultados" class="img-fluid">
                <h4>No se encontraron formaciones</h4>
                <p>Intenta ajustar tus filtros de búsqueda o crear una nueva formación.</p>
                <a href="{{ route('capacitaciones.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>Crear Formación
                </a>
            </div>
        @endif
    </div>

    <!-- CDNs -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Variables de diseño */
        :root {
            --primary-color: #4361ee;
            --primary-light: #6c7ef0;
            --primary-dark: #3a56d4;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --info-color: #4895ef;
            --warning-color: #f8961e;
            --danger-color: #f72585;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --text-color: #2b2d42;
            --text-light: #8d99ae;
            --bg-light: #f8f9fa;
            --border-color: #e9ecef;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            --border-radius: 12px;
            --border-radius-sm: 8px;
        }

        /* Estructura principal */
        .training-list-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        /* Encabezado de página */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2rem;
        }

        .header-content {
            flex: 1;
        }

        .breadcrumb {
            background: none;
            padding: 0;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .breadcrumb-item a {
            color: var(--text-light);
            text-decoration: none;
            transition: var(--transition);
        }

        .breadcrumb-item a:hover {
            color: var(--primary-color);
        }

        .breadcrumb-item.active {
            color: var(--primary-color);
            font-weight: 500;
        }

        .header-content h1 {
            font-size: 2.2rem;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
        }

        .header-content h1 i {
            margin-right: 1rem;
            color: var(--primary-color);
        }

        .subtitle {
            color: var(--text-light);
            font-size: 1.1rem;
            margin: 0;
        }

        /* Botón de creación */
        .create-btn {
            display: inline-flex;
            align-items: center;
            background: var(--primary-color);
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 0.75rem 1.75rem;
            border-radius: var(--border-radius);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
            border: none;
            white-space: nowrap;
        }

        .create-btn i {
            margin-right: 0.75rem;
            font-size: 1.1rem;
        }

        .create-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            color: white;
        }

        /* Búsqueda avanzada */
        .advanced-search-container {
            margin-bottom: 2.5rem;
        }

        .search-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            transition: var(--transition);
        }

        .search-card:hover {
            box-shadow: var(--shadow-md);
        }

        .search-header {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            background: var(--bg-light);
            cursor: pointer;
        }

        .search-header h4 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark-color);
        }

        .search-header i {
            margin-right: 0.75rem;
            color: var(--primary-color);
        }

        .toggle-filters {
            margin-left: auto;
            background: none;
            border: none;
            color: var(--text-light);
            transition: var(--transition);
        }

        .toggle-filters:hover {
            color: var(--primary-color);
        }

        .search-body {
            padding: 1.5rem;
            border-top: 1px solid var(--border-color);
        }

        .search-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-group i {
            position: absolute;
            left: 1rem;
            color: var(--text-light);
        }

        .search-input {
            padding-left: 3rem !important;
            border-radius: var(--border-radius-sm);
            border: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .search-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
        }

        .filter-group {
            margin-bottom: 1rem;
        }

        .filter-group label {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-color);
        }

        .filter-group label i {
            margin-right: 0.5rem;
            color: var(--primary-color);
        }

        .form-select,
        .form-control {
            border-radius: var(--border-radius-sm);
            border: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .form-select:focus,
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
        }

        .date-range {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .date-range span {
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .filter-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 1rem;
        }

        .search-btn,
        .reset-btn {
            border-radius: var(--border-radius-sm);
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            transition: var(--transition);
            display: flex;
            align-items: center;
        }

        .search-btn i,
        .reset-btn i {
            margin-right: 0.5rem;
        }

        .search-btn {
            background: var(--primary-color);
            border: none;
        }

        .search-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .reset-btn {
            border: 1px solid var(--border-color);
        }

        .reset-btn:hover {
            background: var(--bg-light);
        }

        /* Información de resultados */
        .results-info {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
            color: var(--text-color);
        }

        .results-info .badge {
            margin-right: 0.75rem;
            font-weight: 500;
            background: var(--primary-color);
        }

        .clear-filters {
            margin-left: auto;
            color: var(--text-light);
            font-size: 0.9rem;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .clear-filters i {
            margin-right: 0.25rem;
        }

        .clear-filters:hover {
            color: var(--primary-color);
        }

        /* Grid de formaciones */
        .training-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.75rem;
            margin-bottom: 2rem;
        }

        /* Tarjetas de formación */
        .training-card {
            background: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            position: relative;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .training-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        /* Imagen de la tarjeta */
        .card-image {
            position: relative;
            height: 180px;
            overflow: hidden;
        }

        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .training-card:hover .card-image img {
            transform: scale(1.05);
        }

        .image-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 40%;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
        }

        .card-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            color: white;
            z-index: 1;
        }

        .card-badge.upcoming {
            background: var(--success-color);
        }

        .card-badge.past {
            background: var(--warning-color);
        }

        /* Contenido de la tarjeta */
        .card-content {
            padding: 1.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .card-header {
            margin-bottom: 1rem;
        }

        .card-header h3 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--dark-color);
            line-height: 1.4;
        }

        .card-type {
            display: inline-block;
            margin-top: 0.5rem;
            padding: 0.25rem 0.75rem;
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary-color);
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Detalles de la formación */
        .card-details {
            margin-bottom: 1.5rem;
        }

        .detail-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 0.75rem;
            color: var(--text-color);
            font-size: 0.9rem;
        }

        .detail-item i {
            margin-right: 0.75rem;
            color: var(--primary-color);
            margin-top: 0.15rem;
            flex-shrink: 0;
        }

        /* Barra de progreso */
        .progress-container {
            margin-bottom: 1.5rem;
        }

        .progress-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.25rem;
            font-size: 0.8rem;
            color: var(--text-light);
        }

        .progress {
            height: 6px;
            background: var(--border-color);
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-bar {
            background: var(--primary-color);
            transition: width 0.6s ease;
        }

        /* Acciones de la tarjeta */
        .card-actions {
            margin-top: auto;
        }

        .btn-view {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            background: var(--primary-color);
            color: white;
            border-radius: var(--border-radius-sm);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: var(--transition);
            border: none;
            cursor: pointer;
        }

        .btn-view i {
            margin-right: 0.5rem;
        }

        .btn-view:hover {
            background: var(--primary-dark);
        }

        .dropdown-menu {
            border: none;
            box-shadow: var(--shadow-lg);
            border-radius: var(--border-radius-sm);
            padding: 0.5rem;
            min-width: 200px;
        }

        .dropdown-item {
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius-sm);
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            transition: var(--transition);
        }

        .dropdown-item i {
            margin-right: 0.5rem;
            width: 16px;
            text-align: center;
        }

        .dropdown-item:hover {
            background: var(--bg-light);
        }

        /* Paginación */
        .custom-pagination {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
        }

        .pagination {
            display: flex;
            gap: 0.5rem;
        }

        .page-item .page-link {
            border: none;
            color: var(--text-color);
            border-radius: var(--border-radius-sm);
            padding: 0.5rem 0.9rem;
            transition: var(--transition);
        }

        .page-item.active .page-link {
            background: var(--primary-color);
            color: white;
        }

        .page-item:not(.active) .page-link:hover {
            background: var(--bg-light);
        }

        /* Sin resultados */
        .no-results {
            text-align: center;
            padding: 3rem 0;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
        }

        .no-results img {
            max-width: 300px;
            margin-bottom: 1.5rem;
            opacity: 0.7;
        }

        .no-results h4 {
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .no-results p {
            color: var(--text-light);
            margin-bottom: 1.5rem;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .training-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            }
        }

        @media (max-width: 992px) {
            .training-list-container {
                padding: 1.5rem;
            }

            .page-header {
                flex-direction: column;
                gap: 1.5rem;
            }

            .header-actions {
                width: 100%;
            }

            .create-btn {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 768px) {
            .training-grid {
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
                gap: 1.25rem;
            }

            .search-body .row {
                flex-direction: column;
            }

            .date-range {
                flex-direction: column;
                align-items: flex-start;
            }

            .date-range span {
                display: none;
            }

            .filter-actions {
                justify-content: center;
            }
        }

        @media (max-width: 576px) {
            .training-list-container {
                padding: 1rem;
            }

            .header-content h1 {
                font-size: 1.8rem;
            }

            .training-grid {
                grid-template-columns: 1fr;
            }

            .card-image {
                height: 160px;
            }
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Toggle para filtros avanzados
            const toggleFilters = document.getElementById('toggleFilters');
            const searchBody = document.getElementById('searchBody');

            if (toggleFilters && searchBody) {
                // Inicialmente oculto en móviles
                if (window.innerWidth < 768) {
                    searchBody.style.display = 'none';
                    toggleFilters.querySelector('i').className = 'fas fa-chevron-right';
                }

                toggleFilters.addEventListener('click', function() {
                    if (searchBody.style.display === 'none' || !searchBody.style.display) {
                        searchBody.style.display = 'block';
                        this.querySelector('i').className = 'fas fa-chevron-down';
                    } else {
                        searchBody.style.display = 'none';
                        this.querySelector('i').className = 'fas fa-chevron-right';
                    }
                });
            }

            // Resetear filtros
            const resetFilters = document.getElementById('resetFilters');
            if (resetFilters) {
                resetFilters.addEventListener('click', function() {
                    const form = document.getElementById('searchForm');
                    form.reset();
                    form.submit();
                });
            }

            // Validación de fechas
            const desdeInput = document.querySelector('input[name="desde"]');
            const hastaInput = document.querySelector('input[name="hasta"]');

            if (desdeInput && hastaInput) {
                desdeInput.addEventListener('change', function() {
                    if (hastaInput.value && this.value > hastaInput.value) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error en fechas',
                            text: 'La fecha "Desde" no puede ser mayor que la fecha "Hasta"',
                            confirmButtonColor: 'var(--primary-color)'
                        });
                        this.value = '';
                    }
                });

                hastaInput.addEventListener('change', function() {
                    if (desdeInput.value && this.value < desdeInput.value) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error en fechas',
                            text: 'La fecha "Hasta" no puede ser menor que la fecha "Desde"',
                            confirmButtonColor: 'var(--primary-color)'
                        });
                        this.value = '';
                    }
                });
            }
        });

        // Confirmación de eliminación
        function confirmarEliminacion(id) {
            Swal.fire({
                title: '¿Eliminar formación?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'var(--danger-color)',
                cancelButtonColor: 'var(--text-light)',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`eliminar-capacitacion-${id}`).submit();
                }
            });
        }
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
