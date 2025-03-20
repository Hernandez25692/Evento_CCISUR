@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="text-center mb-4">📊 Dashboard de Capacitaciones</h1>

    <!-- Resumen General -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-lg text-center p-3">
                <h3 class="text-primary">📅 Total de Capacitaciones</h3>
                <h2>{{ $totalCapacitaciones }}</h2>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-lg text-center p-3">
                <h3 class="text-success">👥 Total de Participantes</h3>
                <h2>{{ $totalParticipantes }}</h2>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="row">
        <div class="col-md-6">
            <div class="card p-3 shadow-lg">
                <h4 class="text-center">Participantes por Capacitación</h4>
                <canvas id="chartParticipantes"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-3 shadow-lg">
                <h4 class="text-center">Capacitaciones Más Populares</h4>
                <canvas id="chartCapacitaciones"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Incluir Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Datos para el gráfico de barras
    let ctxBar = document.getElementById('chartParticipantes').getContext('2d');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: @json($capacitacionesLabels),
            datasets: [{
                label: 'Participantes',
                data: @json($participantesData),
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Datos para el gráfico de pastel
    let ctxPie = document.getElementById('chartCapacitaciones').getContext('2d');
    new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: @json($capacitacionesLabels),
            datasets: [{
                label: 'Participantes',
                data: @json($participantesData),
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
            }]
        },
        options: {
            responsive: true
        }
    });
</script>

@endsection
