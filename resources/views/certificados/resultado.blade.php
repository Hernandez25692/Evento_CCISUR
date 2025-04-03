@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="text-center mb-4">🎓 Certificados Encontrados</h2>

    @if(!$participante)
        <div class="alert alert-warning text-center">
            <i class="fas fa-exclamation-triangle me-2"></i>
            No se encontró ningún participante con esa identidad.
        </div>
    @else
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-user me-2"></i>{{ $participante->nombre_completo }}</h5>
                <p class="mb-2"><strong>Correo:</strong> {{ $participante->correo }}</p>
                <p class="mb-2"><strong>Identidad:</strong> {{ $participante->identidad }}</p>
                <p class="mb-0"><strong>Total de Capacitaciones:</strong> {{ $participante->capacitaciones->count() }}</p>
            </div>
        </div>

        @if($participante->capacitaciones->isEmpty())
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i>
                Este participante no tiene capacitaciones registradas aún.
            </div>
        @else
            <div class="row g-4">
                @foreach ($participante->capacitaciones as $cap)
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="fas fa-chalkboard-teacher me-2"></i>{{ $cap->nombre }}
                                </h5>
                                <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($cap->fecha_inicio)->format('d/m/Y') }}</p>
                                <a href="{{ route('certificados.descargar', [$cap->id, $participante->id]) }}" class="btn btn-success w-100">
                                    <i class="fas fa-download me-1"></i> Descargar Diploma
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endif
</div>
@endsection
