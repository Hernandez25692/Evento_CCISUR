@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <h2 class="text-center text-primary mb-4"><i class="fas fa-search me-2"></i>Verificación de Diplomas</h2>

        @if (session('error'))
            <div class="alert alert-danger text-center">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('diploma.publico.buscar') }}" class="mb-5">
            @csrf
            <div class="row justify-content-center mb-3">
                <div class="col-md-6">
                    <input type="text" name="identidad" class="form-control form-control-lg"
                        placeholder="Ingrese su número de identidad" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="fas fa-search me-2"></i>Buscar
                    </button>
                </div>
            </div>
        </form>

        @isset($participante)
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Resultados para: {{ $participante->nombre_completo }} ({{ $participante->identidad }})
                    </h5>
                </div>
                <div class="card-body">
                    @if ($capacitaciones->count() > 0)
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Capacitación</th>
                                    <th>Fecha</th>
                                    <th>Duración</th>
                                    <th>Diploma</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($capacitaciones as $index => $cap)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $cap->nombre }}</td>
                                        <td>{{ \Carbon\Carbon::parse($cap->fecha_inicio)->format('d/m/Y') }}</td>
                                        <td>{{ $cap->duracion }} horas</td>
                                        <td>
                                            <a href="{{ route('diplomas.descargar', ['capacitacion_id' => $cap->id, 'identidad' => $participante->identidad]) }}"
                                                class="btn btn-sm btn-success" target="_blank">
                                                <i class="fas fa-file-pdf me-1"></i>Descargar
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-center text-muted">No se encontraron capacitaciones asociadas.</p>
                    @endif
                </div>
            </div>
        @endisset
    </div>
@endsection
