@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="text-center mb-4 text-primary">➕ Agregar Participante a "{{ $capacitacion->nombre }}"</h1>

    <!-- Mensaje de éxito -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form id="form-participante" action="{{ route('capacitaciones.participantes.store', $capacitacion->id) }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre Completo</label>
                        <input type="text" class="form-control" name="nombre_completo" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" name="correo" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input type="text" class="form-control" name="telefono" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Identidad</label>
                        <input type="text" class="form-control" name="identidad" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Empresa</label>
                        <input type="text" class="form-control" name="empresa">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Puesto</label>
                        <input type="text" class="form-control" name="puesto">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Edad</label>
                        <input type="number" class="form-control" name="edad" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Nivel Educativo</label>
                        <select class="form-select" name="nivel_educativo" required>
                            <option value="">Selecciona...</option>
                            <option value="Primaria">Primaria</option>
                            <option value="Secundaria">Secundaria</option>
                            <option value="Universidad">Universidad</option>
                            <option value="Postgrado">Postgrado</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Género</label>
                        <select class="form-select" name="genero" required>
                            <option value="">Selecciona...</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Municipio</label>
                        <input type="text" class="form-control" name="municipio" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Ciudad</label>
                        <input type="text" class="form-control" name="ciudad" required>
                    </div>
                </div>

                <div class="mt-4 d-flex flex-wrap gap-2 justify-content-between">
                    <button type="submit" class="btn btn-success">✅ Guardar Participante</button>
                    <button type="button" class="btn btn-warning" onclick="limpiarFormulario()">🗑️ Vaciar</button>
                    <a href="{{ route('capacitaciones.participantes', $capacitacion->id) }}" class="btn btn-info">🔍 Mostrar Participantes</a>
                    <a href="{{ route('capacitaciones.index') }}" class="btn btn-secondary">🔙 Volver</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Importar desde Excel -->
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-3">📥 Importar Participantes desde Excel</h5>
            <form action="{{ route('participantes.importar', $capacitacion->id) }}" method="POST" enctype="multipart/form-data" class="d-flex flex-column flex-md-row gap-3 align-items-center">
                @csrf
                <input type="file" name="archivo_excel" id="archivo_excel" class="form-control" required>
                <button type="submit" class="btn btn-secondary">📤 Importar</button>
            </form>
        </div>
    </div>
</div>

<script>
    function limpiarFormulario() {
        document.getElementById("form-participante").reset();
    }
</script>
@endsection
