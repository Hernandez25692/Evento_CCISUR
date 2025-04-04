@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Encabezado con efecto gradiente -->
    <div class="dashboard-header mb-5">
        <h1 class="display-5 fw-bold text-white text-center mb-3">📊 Panel de Control - Formaciones</h1>
        <p class="text-center text-white-50 mb-0">Visualización de métricas y estadísticas de capacitaciones</p>
    </div>

    <!-- Tarjetas Resumen con animaciones -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card stat-card shadow-lg h-100 hover-scale">
                <div class="card-body text-center">
                    <div class="stat-icon bg-primary-light">
                        <i class="fas fa-folder-open text-primary"></i>
                    </div>
                    <h3 class="stat-title">Total de Formaciones</h3>
                    <h2 class="stat-value text-primary">{{ $totalCapacitaciones }}</h2>
                    <p class="stat-desc">Capacitaciones registradas</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card stat-card shadow-lg h-100 hover-scale">
                <div class="card-body text-center">
                    <div class="stat-icon bg-success-light">
                        <i class="fas fa-user text-success"></i>
                    </div>
                    <h3 class="stat-title">Participantes Únicos</h3>
                    <h2 class="stat-value text-success">{{ $totalParticipantesUnicos }}</h2>
                    <p class="stat-desc">Personas distintas capacitadas</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card stat-card shadow-lg h-100 hover-scale">
                <div class="card-body text-center">
                    <div class="stat-icon bg-warning-light">
                        <i class="fas fa-users text-warning"></i>
                    </div>
                    <h3 class="stat-title">Total Participaciones</h3>
                    <h2 class="stat-value text-warning">{{ $totalParticipaciones }}</h2>
                    <p class="stat-desc">Asistencias registradas</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de Gráficos -->
    <div class="row g-4">
        <!-- Gráfico 1: Participantes por Formación -->
        <div class="col-xl-6">
            <div class="card shadow-sm border-0 h-100 chart-card">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users me-2 text-primary"></i>Participantes por Formación
                    </h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            Opciones
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="{{ route('reportes.capacitaciones') }}">Exportar Reporte</a></li>
                            <li><a class="dropdown-item" href="{{ route('dashboard.filtro') }}">Filtrar</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body position-relative">
                    <div class="chart-container">
                        <canvas id="participantesChart"></canvas>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 text-muted small">
                    <i class="fas fa-info-circle me-1"></i> Haga clic en las barras para más detalles
                </div>
            </div>
        </div>

        <!-- Gráfico 2: Formaciones por Mes -->
        <div class="col-xl-6">
            <div class="card shadow-sm border-0 h-100 chart-card">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-alt me-2 text-info"></i>Formaciones por Mes
                    </h5>
                </div>
                <div class="card-body position-relative">
                    <div class="chart-container">
                        <canvas id="capacitacionesMesChart"></canvas>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 text-muted small">
                    <i class="fas fa-info-circle me-1"></i> Datos del último año
                </div>
            </div>
        </div>

        <!-- Gráfico 3: Distribución por Género -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100 chart-card">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-venus-mars me-2 text-pink"></i>Distribución por Género
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container-pie">
                        <canvas id="generoChart"></canvas>
                    </div>
                </div>
                <div class="card-footer bg-white border-0">
                    <div class="row text-center">
                        <div class="col-6">
                            <span class="badge bg-primary">Hombres: {{ $generoData[0] ?? 0 }}</span>
                        </div>
                        <div class="col-6">
                            <span class="badge bg-warning">Mujeres: {{ $generoData[1] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfico 4: Participantes por Edad -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100 chart-card">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2 text-success"></i>Participantes por Edad
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="edadChart"></canvas>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 text-muted small">
                    <i class="fas fa-info-circle me-1"></i> Distribución por rangos de edad
                </div>
            </div>
        </div>

        <!-- Gráfico 5: Recaudación por Formación -->
        <div class="col-12">
            <div class="card shadow-sm border-0 h-100 chart-card">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-money-bill-wave me-2 text-success"></i>Recaudación por Formación
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container-wide">
                        <canvas id="recaudacionChart"></canvas>
                    </div>
                </div>
                <div class="card-footer bg-white border-0">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <p class="mb-0 small text-muted">
                                <i class="fas fa-info-circle me-1"></i> Solo capacitaciones de pago
                            </p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h4 class="mb-0 text-success">Total: L. {{ number_format($totalDineroRecaudado, 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Font Awesome para íconos -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<style>
    :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --success-color: #4cc9f0;
        --warning-color: #f8961e;
        --danger-color: #f72585;
        --light-bg: #f8f9fa;
    }
    
    .dashboard-header {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        padding: 2rem 1rem;
        border-radius: 12px;
        box-shadow: 0 10px 20px rgba(67, 97, 238, 0.2);
    }
    
    .stat-card {
        border: none;
        border-radius: 12px;
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
        background: white;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary-color);
    }
    
    .stat-card:nth-child(2)::before {
        background: var(--success-color);
    }
    
    .stat-card:nth-child(3)::before {
        background: var(--warning-color);
    }
    
    .hover-scale:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        margin: 0 auto 1rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    
    .bg-primary-light {
        background-color: rgba(67, 97, 238, 0.1);
    }
    
    .bg-success-light {
        background-color: rgba(76, 201, 240, 0.1);
    }
    
    .bg-warning-light {
        background-color: rgba(248, 150, 30, 0.1);
    }
    
    .stat-title {
        font-size: 1rem;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }
    
    .stat-value {
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .stat-desc {
        font-size: 0.85rem;
        color: #adb5bd;
        margin-bottom: 0;
    }
    
    .chart-card {
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .chart-card:hover {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .chart-container {
        position: relative;
        height: 250px;
        width: 100%;
    }
    
    .chart-container-pie {
        position: relative;
        height: 220px;
        width: 100%;
    }
    
    .chart-container-wide {
        position: relative;
        height: 300px;
        width: 100%;
    }
    
    .card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .card-title {
        font-weight: 600;
        color: #495057;
    }
    
    .card-footer {
        padding: 1rem 1.5rem;
    }
    
    @media (max-width: 768px) {
        .stat-value {
            font-size: 1.8rem;
        }
        
        .chart-container {
            height: 200px;
        }
        
        .chart-container-wide {
            height: 250px;
        }
    }
</style>

<script>
    // Configuración común para todos los gráficos
    Chart.defaults.font.family = "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif";
    Chart.defaults.color = '#6c757d';
    Chart.defaults.borderColor = 'rgba(0, 0, 0, 0.05)';
    
    // PARTICIPANTES POR CAPACITACIÓN
    const ctx1 = document.getElementById('participantesChart').getContext('2d');
    const gradient1 = ctx1.createLinearGradient(0, 0, 600, 0);
    gradient1.addColorStop(0, '#4361ee');
    gradient1.addColorStop(1, '#4cc9f0');
    
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: {!! json_encode($capacitacionesLabels) !!},
            datasets: [{
                label: 'Participantes',
                data: {!! json_encode($participantesData) !!},
                backgroundColor: gradient1,
                borderRadius: 6,
                borderWidth: 0,
                hoverBackgroundColor: '#3a0ca3'
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    backgroundColor: '#212529',
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: context => ` ${context.parsed.x} participantes`,
                        title: items => `📌 ${items[0].label}`
                    }
                },
                legend: { display: false }
            },
            scales: {
                x: { 
                    beginAtZero: true,
                    grid: { display: false }
                },
                y: {
                    grid: { display: false }
                }
            }
        }
    });
    
    // FORMACIONES POR MES
    const ctx2 = document.getElementById('capacitacionesMesChart').getContext('2d');
    const gradient2 = ctx2.createLinearGradient(0, 0, 0, 300);
    gradient2.addColorStop(0, 'rgba(67, 97, 238, 0.2)');
    gradient2.addColorStop(1, 'rgba(76, 201, 240, 0)');
    
    new Chart(ctx2, {
        type: 'line',
        data: {
            labels: {!! json_encode($capacitacionesPorMes->pluck('mes')) !!},
            datasets: [{
                label: 'Total de Formaciones',
                data: {!! json_encode($capacitacionesPorMes->pluck('total')) !!},
                backgroundColor: gradient2,
                borderColor: '#4361ee',
                borderWidth: 3,
                fill: true,
                tension: 0.3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#4361ee',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#212529',
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: context => ` ${context.parsed.y} capacitaciones`
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 },
                    grid: { display: false }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
    
    // DISTRIBUCIÓN POR GÉNERO
    const ctx3 = document.getElementById('generoChart').getContext('2d');
    new Chart(ctx3, {
        type: 'doughnut',
        data: {
            labels: ['Masculino', 'Femenino'],
            datasets: [{
                data: {!! json_encode($generoData) !!},
                backgroundColor: ['#4361ee', '#f72585', '#4cc9f0'],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    backgroundColor: '#212529',
                    bodyFont: { size: 13 },
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: context => ` ${context.parsed} participantes (${Math.round(context.parsed/context.dataset.data.reduce((a, b) => a + b, 0)*100)}%)`
                    }
                }
            }
        }
    });
    
    // DISTRIBUCIÓN POR EDAD
    const ctxEdad = document.getElementById('edadChart').getContext('2d');
    new Chart(ctxEdad, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($rangosEdad)) !!},
            datasets: [{
                label: 'Participantes',
                data: {!! json_encode(array_values($rangosEdad)) !!},
                backgroundColor: '#4cc9f0',
                borderRadius: 6,
                borderWidth: 0,
                hoverBackgroundColor: '#3a0ca3'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#212529',
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: context => ` ${context.parsed.y} participantes`
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 },
                    grid: { display: false }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
    
    // RECAUDACIÓN POR FORMACIÓN
    const ctxRecaudacion = document.getElementById('recaudacionChart').getContext('2d');
    const gradientRecaudacion = ctxRecaudacion.createLinearGradient(0, 0, 600, 0);
    gradientRecaudacion.addColorStop(0, '#4cc9f0');
    gradientRecaudacion.addColorStop(1, '#4895ef');
    
    new Chart(ctxRecaudacion, {
        type: 'bar',
        data: {
            labels: {!! json_encode($recaudacionLabels) !!},
            datasets: [{
                label: 'Total Recaudado (L.)',
                data: {!! json_encode($recaudacionTotales) !!},
                backgroundColor: gradientRecaudacion,
                borderRadius: 6,
                borderWidth: 0,
                hoverBackgroundColor: '#3a0ca3'
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#212529',
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: context => ` L. ${context.parsed.x.toLocaleString('es-HN')}`
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => `L. ${value.toLocaleString('es-HN')}`
                    },
                    grid: { display: false }
                },
                y: {
                    grid: { display: false }
                }
            }
        }
    });
</script>
@endsection