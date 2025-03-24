@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="text-center mb-4">📄 Plantilla para: {{ $capacitacion->nombre }}</h2>

    <!-- Mensajes -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Formulario para subir la plantilla -->
    <form action="{{ route('capacitaciones.plantilla.store', $capacitacion->id) }}" method="POST" enctype="multipart/form-data" class="mb-4">
        @csrf
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Firma</label>
                <input type="file" name="firma" class="form-control" accept="image/*" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Fondo</label>
                <input type="file" name="fondo" class="form-control" accept="image/*" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Fecha de Emisión</label>
                <input type="date" name="fecha_emision" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Orientación</label>
                <select name="orientacion" class="form-select" required>
                    <option value="horizontal">Horizontal</option>
                    <option value="vertical">Vertical</option>
                </select>
            </div>
            <div class="col-md-12 text-center mt-3">
                <button type="submit" class="btn btn-success px-4">💾 Guardar Plantilla</button>
            </div>
        </div>
    </form>

    <!-- Botones de acciones (sólo si existe plantilla) -->
    @if ($plantillaExistente)
        <div class="text-center mt-4">
            <a href="{{ route('capacitaciones.diplomas.preview', $capacitacion->id) }}" target="_blank" class="btn btn-info me-2">
                👁️ Vista Previa
            </a>
            <a href="{{ route('capacitaciones.diplomas', $capacitacion->id) }}" class="btn btn-primary">
                🧾 Generar Diplomas
            </a>
        </div>
    @endif
</div>
@endsection
