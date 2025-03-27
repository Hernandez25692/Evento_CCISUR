@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="text-center text-primary mb-5">📊 Panel de Control - Formaciones</h1>

    <!-- Tarjetas Resumen -->
    <div class="row g-4 mb-5 text-center">
        <div class="col-md-4">
            <div class="card border-0 shadow h-100 p-3">
                <div class="card-body">
                    <div class="fs-2 text-primary mb-2">📁</div>
                    <h5>Total de Formaciones</h5>
                    <h2 class="fw-bold text-primary">{{ $totalCapacitaciones }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow h-100 p-3">
                <div class="card-body">
                    <div class="fs-2 text-success mb-2">👤</div>
                    <h5>Participantes Únicos</h5>
                    <h2 class="fw-bold text-success">{{ $totalParticipantesUnicos }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow h-100 p-3">
                <div class="card-body">
                    <div class="fs-2 text-warning mb-2">👥</div>
                    <h5>Total de Participaciones</h5>
                    <h2 class="fw-bold text-warning">{{ $totalParticipaciones }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficas -->
    <div class="row g-4">
        <!-- Participantes por Formación -->
        <div class="col-lg-6">
            <div class="card shadow border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title text-center mb-3">👥 Participantes por Formación</h5>
                    <div style="overflow-x: auto;">
                        <div style="min-width: 500px;">
                            <canvas id="participantesChart" height="240"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formaciones por Mes -->
        <div class="col-lg-6">
            <div class="card shadow border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title text-center mb-3">📅 Formaciones por Mes</h5>
                    <canvas id="capacitacionesMesChart" height="240"></canvas>
                </div>
            </div>
        </div>

        <!-- Participantes por Género -->
        <div class="col-lg-6">
            <div class="card shadow border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title text-center mb-3">🚻 Distribución por Género</h5>
                    <canvas id="generoChart" height="240"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // PARTICIPANTES POR CAPACITACIÓN
    const ctx1 = document.getElementById('participantesChart').getContext('2d');
    const gradient1 = ctx1.createLinearGradient(0, 0, 600, 0);
    gradient1.addColorStop(0, '#0d6efd');
    gradient1.addColorStop(1, '#00c6ff');

    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: {!! json_encode($capacitacionesLabels) !!},
            datasets: [{
                label: 'Participantes',
                data: {!! json_encode($participantesData) !!},
                backgroundColor: gradient1,
                borderRadius: 8
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: context => `👤 ${context.parsed.x} participantes`
                    }
                },
                legend: { display: false }
            },
            scales: {
                x: { beginAtZero: true }
            }
        }
    });

    // FORMACIONES POR MES
    const ctx2 = document.getElementById('capacitacionesMesChart').getContext('2d');
    const gradient2 = ctx2.createLinearGradient(0, 0, 0, 300);
    gradient2.addColorStop(0, 'rgba(13, 110, 253, 0.4)');
    gradient2.addColorStop(1, 'rgba(255,255,255,0)');

    new Chart(ctx2, {
        type: 'line',
        data: {
            labels: {!! json_encode($capacitacionesPorMes->pluck('mes')) !!},
            datasets: [{
                label: 'Total de Formaciones',
                data: {!! json_encode($capacitacionesPorMes->pluck('total')) !!},
                backgroundColor: gradient2,
                borderColor: '#0d6efd',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#0d6efd',
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true, position: 'top' },
                tooltip: {
                    callbacks: {
                        label: context => `📅 ${context.parsed.y} capacitaciones`
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
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
                backgroundColor: ['#0d6efd', '#ffc107', '#6c757d'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endsection
