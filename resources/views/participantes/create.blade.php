@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center mb-4">Agregar Participante a {{ $capacitacion->nombre }}</h1>

    <!-- Mensaje de éxito -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form id="form-participante" action="{{ route('capacitaciones.participantes.store', $capacitacion->id) }}" method="POST">
        @csrf
        
        <div class="mb-3">
            <label class="form-label">Nombre Completo</label>
            <input type="text" class="form-control" name="nombre_completo" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" name="correo" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Teléfono</label>
            <input type="text" class="form-control" name="telefono" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Empresa</label>
            <input type="text" class="form-control" name="empresa">
        </div>

        <div class="mb-3">
            <label class="form-label">Puesto</label>
            <input type="text" class="form-control" name="puesto">
        </div>

        <div class="mb-3">
            <label class="form-label">Edad</label>
            <input type="number" class="form-control" name="edad" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Identidad</label>
            <input type="text" class="form-control" name="identidad" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nivel Educativo</label>
            <select class="form-control" name="nivel_educativo" required>
                <option value="Primaria">Primaria</option>
                <option value="Secundaria">Secundaria</option>
                <option value="Universidad">Universidad</option>
                <option value="Postgrado">Postgrado</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Género</label>
            <select class="form-control" name="genero" required>
                <option value="Masculino">Masculino</option>
                <option value="Femenino">Femenino</option>
                <option value="Otro">Otro</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Municipio</label>
            <input type="text" class="form-control" name="municipio" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Ciudad</label>
            <input type="text" class="form-control" name="ciudad" required>
        </div>

        <button type="submit" class="btn btn-success">✅ Guardar Participante</button>
        <button type="button" class="btn btn-warning" onclick="limpiarFormulario()">🗑️ Vaciar</button>
        <a href="{{ route('capacitaciones.participantes', $capacitacion->id) }}" class="btn btn-info">🔍 Mostrar Participantes</a>
        <a href="{{ route('capacitaciones.index') }}" class="btn btn-secondary">🔙 Volver</a>
    </form>
</div>

<script>
    function limpiarFormulario() {
        document.getElementById("form-participante").reset();
    }
</script>

@endsection
