@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="text-center mb-5 fw-bold text-primary-emphasis">
        📊 Dashboard de Capacitaciones
    </h1>

    <!-- Resumen General -->
    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 bg-light h-100">
                <div class="card-body text-center">
                    <h5 class="card-title text-primary fw-bold mb-2">📅 Total de Capacitaciones</h5>
                    <h2 class="display-5">{{ $totalCapacitaciones }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 bg-light h-100">
                <div class="card-body text-center">
                    <h5 class="card-title text-success fw-bold mb-2">👥 Total de Participantes</h5>
                    <h2 class="display-5">{{ $totalParticipantes }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 h-100">
                <div class="card-header bg-primary text-white text-center fw-bold">
                    Participantes por Capacitación
                </div>
                <div class="card-body">
                    <canvas id="chartParticipantes" height="230"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-lg border-0 h-100">
                <div class="card-header bg-success text-white text-center fw-bold">
                    Capacitaciones Más Populares
                </div>
                <div class="card-body">
                    <canvas id="chartCapacitaciones" height="230"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Gráficos -->
<script>
    const participantesLabels = @json($capacitacionesLabels);
    const participantesData = @json($participantesData);

    new Chart(document.getElementById('chartParticipantes'), {
        type: 'bar',
        data: {
            labels: participantesLabels,
            datasets: [{
                label: 'Participantes',
                data: participantesData,
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                borderRadius: 5,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });

    new Chart(document.getElementById('chartCapacitaciones'), {
        type: 'doughnut',
        data: {
            labels: participantesLabels,
            datasets: [{
                label: 'Participantes',
                data: participantesData,
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                    '#FF9F40', '#C9CBCF', '#9AD0F5'
                ],
                borderColor: '#fff',
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
    