@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="text-center text-primary mb-5">📊 Resumen General de Formaciones</h1>

    <!-- Tarjetas de estadísticas -->
    <div class="row g-4 justify-content-center text-center">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 p-3 h-100">
                <div class="card-body">
                    <div class="text-primary fs-2 mb-2">📁</div>
                    <h5 class="card-title">Total de Formaciones</h5>
                    <h2 class="fw-bold text-primary">{{ $totalCapacitaciones }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 p-3 h-100">
                <div class="card-body">
                    <div class="text-success fs-2 mb-2">👤</div>
                    <h5 class="card-title">Participantes Únicos</h5>
                    <h2 class="fw-bold text-success">{{ $totalParticipantesUnicos }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 p-3 h-100">
                <div class="card-body">
                    <div class="text-warning fs-2 mb-2">👥</div>
                    <h5 class="card-title">Total de Participaciones</h5>
                    <h2 class="fw-bold text-warning">{{ $totalParticipaciones }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Espaciado -->
    <hr class="my-5">

    <!-- Gráfico de barras horizontal -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body">
            <h5 class="card-title mb-4 text-center">👥 Participantes por Formación</h5>
            <canvas id="participantesChart"></canvas>
        </div>
    </div>

    <!-- Gráfico de línea -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body">
            <h5 class="card-title mb-4 text-center">📅 Formaciones por Mes</h5>
            <canvas id="capacitacionesMesChart"></canvas>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Gráfico de barras horizontal
    const ctx1 = document.getElementById('participantesChart').getContext('2d');
    const participantesChart = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: {!! json_encode($capacitacionesLabels) !!},
            datasets: [{
                label: 'Participantes',
                data: {!! json_encode($participantesData) !!},
                backgroundColor: '#0d6efd'
            }]
        },
        options: {
            indexAxis: 'y', // Barra horizontal
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true
                }
            }
        }
    });

    // Gráfico de línea suave
    const ctx2 = document.getElementById('capacitacionesMesChart').getContext('2d');
    const capacitacionesMesChart = new Chart(ctx2, {
        type: 'line',
        data: {
            labels: {!! json_encode($capacitacionesPorMes->pluck('mes')) !!},
            datasets: [{
                label: 'Capacitaciones',
                data: {!! json_encode($capacitacionesPorMes->pluck('total')) !!},
                backgroundColor: 'rgba(13, 110, 253, 0.2)',
                borderColor: '#0d6efd',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#0d6efd'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection
