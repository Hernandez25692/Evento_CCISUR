@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="text-center mb-4">📊 Filtro de Participantes</h1>

    <!-- Formulario de Filtro -->
    <form method="GET" action="{{ route('dashboard.filtro') }}" class="mb-4">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Edad</label>
                <input type="number" name="edad" class="form-control" placeholder="Ingrese edad" value="{{ request('edad') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Género</label>
                <select name="genero" class="form-select">
                    <option value="">Todos</option>
                    <option value="Masculino" {{ request('genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                    <option value="Femenino" {{ request('genero') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                    <option value="Otro" {{ request('genero') == 'Otro' ? 'selected' : '' }}>Otro</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Nivel Educativo</label>
                <select name="nivel_educativo" class="form-select">
                    <option value="">Todos</option>
                    <option value="Primaria" {{ request('nivel_educativo') == 'Primaria' ? 'selected' : '' }}>Primaria</option>
                    <option value="Secundaria" {{ request('nivel_educativo') == 'Secundaria' ? 'selected' : '' }}>Secundaria</option>
                    <option value="Universidad" {{ request('nivel_educativo') == 'Universidad' ? 'selected' : '' }}>Universidad</option>
                    <option value="Postgrado" {{ request('nivel_educativo') == 'Postgrado' ? 'selected' : '' }}>Postgrado</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Empresa</label>
                <input type="text" name="empresa" class="form-control" placeholder="Ingrese empresa" value="{{ request('empresa') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Municipio</label>
                <input type="text" name="municipio" class="form-control" placeholder="Ingrese municipio" value="{{ request('municipio') }}">
            </div>
            <div class="col-md-3 align-self-end">
                <button type="submit" class="btn btn-primary w-100">🔍 Filtrar</button>
            </div>
        </div>
    </form>

    <!-- Tabla de Resultados -->
    @if($participantes->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover table-bordered shadow-sm">
            <thead class="table-dark text-center">
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Empresa</th>
                    <th>Edad</th>
                    <th>Nivel Educativo</th>
                    <th>Género</th>
                    <th>Municipio</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($participantes as $index => $participante)
                <tr class="align-middle">
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $participante->nombre_completo }}</td>
                    <td>{{ $participante->correo }}</td>
                    <td>{{ $participante->telefono }}</td>
                    <td>{{ $participante->empresa ?? '-' }}</td>
                    <td class="text-center">{{ $participante->edad }}</td>
                    <td class="text-center">{{ $participante->nivel_educativo }}</td>
                    <td class="text-center">{{ $participante->genero }}</td>
                    <td class="text-center">{{ $participante->municipio }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <p class="text-center alert alert-warning">⚠️ No hay resultados para los filtros aplicados.</p>
    @endif
</div>

<style>
    .table {
        border-radius: 10px;
        overflow: hidden;
    }

    .table thead {
        background-color: #007bff;
        color: white;
    }

    .table-hover tbody tr:hover {
        background-color: #f1f1f1;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }
</style>

@endsection
