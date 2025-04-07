@extends('layouts.app')

@section('content')
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --success-color: #27ae60;
            --info-color: #2980b9;
            --warning-color: #f39c12;
            --light-bg: #f8f9fa;
            --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --transition-speed: 0.3s;
        }

        /* Estructura principal */
        .report-container {
            background-color: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            padding: 2.5rem;
            margin-top: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .report-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(to bottom, var(--primary-color), var(--secondary-color));
        }

        /* Títulos y encabezados */
        .report-title {
            color: var(--secondary-color);
            font-weight: 700;
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
            padding-bottom: 1rem;
        }

        .report-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(to right, var(--primary-color), var(--success-color));
            border-radius: 2px;
        }

        .section-title {
            color: var(--secondary-color);
            font-weight: 600;
            margin: 2rem 0 1.5rem;
            padding-left: 1rem;
            border-left: 4px solid var(--primary-color);
        }

        /* Formulario de filtros */
        .filter-form {
            background-color: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 2.5rem;
            transition: all var(--transition-speed) ease;
        }

        .filter-form:hover {
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 0.6rem 1rem;
            transition: all var(--transition-speed) ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.15);
        }

        /* Botones */
        .btn {
            border-radius: 6px;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: all var(--transition-speed) ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.6rem 1.25rem;
        }

        .btn i {
            margin-right: 8px;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
            transform: translateY(-2px);
        }

        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }

        .btn-success:hover {
            background-color: #219653;
            border-color: #219653;
            transform: translateY(-2px);
        }

        .btn-info {
            background-color: var(--info-color);
            border-color: var(--info-color);
        }

        .btn-info:hover {
            background-color: #2472a4;
            border-color: #2472a4;
            transform: translateY(-2px);
        }

        /* Tablas */
        .table-wrapper {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 2.5rem;
            position: relative;
        }

        .table {
            margin-bottom: 0;
            font-size: 0.95rem;
        }

        .table thead th {
            vertical-align: middle;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            font-weight: 600;
            padding: 1rem;
        }

        .table tbody td {
            vertical-align: middle;
            padding: 0.85rem 1rem;
        }

        .table-dark {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        .table-dark th {
            border-bottom: none;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(52, 152, 219, 0.03);
        }

        .table-hover tbody tr {
            transition: all 0.2s ease;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.1);
            transform: scale(1.005);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        /* Alertas */
        .custom-alert {
            border-radius: 8px;
            padding: 1.25rem;
            text-align: center;
            font-weight: 500;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        /* Animaciones */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animated-table {
            animation: fadeInUp 0.5s ease forwards;
        }

        /* Badges para datos importantes */
        .data-badge {
            display: inline-block;
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
            background-color: rgba(52, 152, 219, 0.1);
            color: var(--secondary-color);
        }

        /* Tooltip personalizado */
        [data-bs-toggle="tooltip"] {
            cursor: pointer;
            position: relative;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .report-container {
                padding: 1.5rem;
            }

            .table-responsive {
                border: 1px solid #eee;
            }
        }

        @media (max-width: 768px) {
            .filter-form {
                padding: 1rem;
            }

            .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }
        }

        /* Efecto de carga */
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .spinner {
            width: 3rem;
            height: 3rem;
            border: 5px solid #f3f3f3;
            border-top: 5px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    <!-- Overlay de carga -->
    <div class="loading-overlay">
        <div class="spinner"></div>
    </div>

    <div class="container py-4">
        <div class="report-container">
            <h2 class="report-title"><i class="fas fa-chart-line me-2"></i>Reporte de Capacitaciones</h2>

            <form method="GET" action="{{ route('reportes.capacitaciones') }}" class="filter-form" id="reportForm">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="desde" class="form-label">Desde</label>
                        <input type="date" id="desde" name="desde" class="form-control"
                            value="{{ request('desde') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="hasta" class="form-label">Hasta</label>
                        <input type="date" id="hasta" name="hasta" class="form-control"
                            value="{{ request('hasta') }}" required>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-1"></i> Generar Reporte
                        </button>
                    </div>
                </div>
            </form>

            @if (isset($capacitaciones) && count($capacitaciones))
                <div class="mb-4 d-flex flex-wrap gap-3">
                    <a href="{{ route('reportes.capacitaciones.export', ['desde' => request('desde'), 'hasta' => request('hasta'), 'tipo' => 'resumen']) }}"
                        class="btn btn-success" data-bs-toggle="tooltip" title="Exportar resumen a Excel">
                        <i class="fas fa-file-excel me-1"></i> Exportar Resumen
                    </a>
                    <a href="{{ route('reportes.capacitaciones.export', ['desde' => request('desde'), 'hasta' => request('hasta'), 'tipo' => 'detalle']) }}"
                        class="btn btn-info" data-bs-toggle="tooltip" title="Exportar detalle completo a Excel">
                        <i class="fas fa-file-excel me-1"></i> Exportar Detalle
                    </a>
                </div>

                <!-- Resumen General -->
                <h4 class="section-title"><i class="fas fa-table me-2"></i>Resumen General</h4>
                <div class="table-wrapper animated-table">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Fecha</th>
                                    <th>Tipo</th>
                                    <th>Modalidad</th>
                                    <th>Duración</th>
                                    <th>Medio</th>
                                    <th>Total Participantes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($capacitaciones as $c)
                                    <tr>
                                        <td>{{ $c->nombre }}</td>
                                        <td>{{ $c->fecha }}</td>
                                        <td><span class="data-badge">{{ $c->tipo_formacion ?? 'N/A' }}</span></td>
                                        <td>{{ $c->forma ?? 'N/A' }}</td>
                                        <td>{{ $c->duracion ?? 'N/A' }}</td>
                                        <td>{{ $c->medio ?? 'N/A' }}</td>
                                        <td><span class="data-badge">{{ $c->participantes_count }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Detalle de Capacitaciones -->
                <h4 class="section-title"><i class="fas fa-list me-2"></i>Detalle por Capacitación</h4>
                <div class="table-wrapper animated-table">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Fecha</th>
                                    <th>Impartido Por</th>
                                    <th>Lugar</th>
                                    <th>Tipo</th>
                                    <th>Duración</th>
                                    <th>Modalidad</th>
                                    <th>Medio</th>
                                    <th>Cupos</th>
                                    <th>Límite</th>
                                    <th>Precio Afiliado</th>
                                    <th>ISV Afiliado</th>
                                    <th>Precio No Afiliado</th>
                                    <th>ISV No Afiliado</th>
                                    <th>Total Participantes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($capacitaciones as $c)
                                    <tr>
                                        <td>{{ $c->nombre }}</td>
                                        <td>{{ $c->fecha }}</td>
                                        <td>{{ $c->impartido_por }}</td>
                                        <td>{{ $c->lugar }}</td>
                                        <td><span class="data-badge">{{ $c->tipo_formacion ?? 'N/A' }}</span></td>
                                        <td>{{ $c->duracion ?? 'N/A' }}</td>
                                        <td>{{ $c->forma ?? 'N/A' }}</td>
                                        <td>{{ $c->medio }}</td>
                                        <td>{{ ucfirst($c->cupos) }}</td>
                                        <td>{{ $c->limite_participantes ?? 'N/A' }}</td>
                                        <td>L. {{ number_format($c->precio_afiliado, 2) }}</td>
                                        <td>L. {{ number_format($c->isv_afiliado, 2) }}</td>
                                        <td>L. {{ number_format($c->precio_no_afiliado, 2) }}</td>
                                        <td>L. {{ number_format($c->isv_no_afiliado, 2) }}</td>
                                        <td><span class="data-badge">{{ $c->participantes_count }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @elseif(request()->filled(['desde', 'hasta']))
                <div class="custom-alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i>No se encontraron capacitaciones en el rango seleccionado.
                </div>
            @endif
        </div>
    </div>

    <!-- JavaScript para mejorar la interactividad -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mostrar overlay de carga al enviar el formulario
            document.getElementById('reportForm').addEventListener('submit', function() {
                document.querySelector('.loading-overlay').style.display = 'flex';
            });

            // Inicializar tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Validación de fechas
            const desdeInput = document.getElementById('desde');
            const hastaInput = document.getElementById('hasta');

            if (desdeInput && hastaInput) {
                desdeInput.addEventListener('change', function() {
                    if (hastaInput.value && this.value > hastaInput.value) {
                        alert('La fecha "Desde" no puede ser mayor que la fecha "Hasta"');
                        this.value = '';
                    }
                });

                hastaInput.addEventListener('change', function() {
                    if (desdeInput.value && this.value < desdeInput.value) {
                        alert('La fecha "Hasta" no puede ser menor que la fecha "Desde"');
                        this.value = '';
                    }
                });
            }
        });
    </script>
@endsection
