@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="text-center mb-4 text-primary">➕ Agregar Participante a "{{ $capacitacion->nombre }}"</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif


    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form id="form-participante" action="{{ route('capacitaciones.participantes.store', $capacitacion->id) }}" method="POST" enctype="multipart/form-data">
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
                            <option>Universitaria Completa</option>
                            <option>Universitaria Incompleta</option>
                            <option>Técnico Completo</option>
                            <option>Técnico Incompleto</option>
                            <option>Secundaria Completa</option>
                            <option>Secundaria Incompleta</option>
                            <option>Primaria Completa</option>
                            <option>Primaria Incompleta</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Género</label>
                        <select class="form-select" name="genero" required>
                            <option value="">Selecciona...</option>
                            <option>Masculino</option>
                            <option>Femenino</option>
                            <option>Otro</option>
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

                    <div class="col-md-6">
                        <label class="form-label">¿Es afiliado?</label>
                        <select name="afiliado" id="afiliado" class="form-select" required>
                            <option value="0">No</option>
                            <option value="1">Sí</option>
                        </select>
                    </div>

                    @if(isset($capacitacion->medio) && strtolower($capacitacion->medio) == 'pago')
                    <div class="col-md-6">
                        <label class="form-label">Comprobante de Pago</label>
                        <input type="file" class="form-control" name="comprobante" accept=".jpg,.jpeg,.png,.pdf">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Precio Base</label>
                        <input type="text" class="form-control" id="precio" readonly>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">ISV</label>
                        <input type="text" class="form-control" id="isv" readonly>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Total a Pagar</label>
                        <input type="text" class="form-control" name="total" id="total" readonly>
                    </div>
                    @endif
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
        calcularTotal();
    }

    document.addEventListener('DOMContentLoaded', function () {
        const afiliado = document.getElementById('afiliado');
        const precio = document.getElementById('precio');
        const isv = document.getElementById('isv');
        const total = document.getElementById('total');

        function calcularTotal() {
            let base = 0;
            let impuesto = 0;

            @if(isset($capacitacion->medio) && strtolower($capacitacion->medio) == 'pago')
                const esAfiliado = parseInt(afiliado.value);
                base = esAfiliado ? {{ $capacitacion->precio_afiliado }} : {{ $capacitacion->precio_no_afiliado }};
                impuesto = esAfiliado ? {{ $capacitacion->isv_afiliado }} : {{ $capacitacion->isv_no_afiliado }};
            @endif

            if (precio) precio.value = base.toFixed(2);
            if (isv) isv.value = impuesto.toFixed(2);
            if (total) total.value = (base + impuesto).toFixed(2);
        }

        if (afiliado) {
            afiliado.addEventListener('change', calcularTotal);
            calcularTotal();
        }
    });
</script>
@endsection
