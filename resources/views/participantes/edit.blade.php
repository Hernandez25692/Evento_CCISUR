@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="text-center mb-4 text-warning">✏️ Editar Participante de "{{ $capacitacion->nombre }}"</h1>

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
            <form id="form-participante" action="{{ route('participantes.update', [$capacitacion->id, $participante->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre Completo</label>
                        <input type="text" class="form-control" name="nombre_completo" value="{{ $participante->nombre_completo }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" name="correo" value="{{ $participante->correo }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input type="text" class="form-control" name="telefono" value="{{ $participante->telefono }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Identidad</label>
                        <input type="text" class="form-control" name="identidad" value="{{ $participante->identidad }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Empresa</label>
                        <input type="text" class="form-control" name="empresa" value="{{ $participante->empresa }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Puesto</label>
                        <input type="text" class="form-control" name="puesto" value="{{ $participante->puesto }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Edad</label>
                        <input type="number" class="form-control" name="edad" value="{{ $participante->edad }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Nivel Educativo</label>
                        <select class="form-select" name="nivel_educativo" required>
                            @php
                                $niveles = [
                                    'Universitaria Completa', 'Universitaria Incompleta',
                                    'Técnico Completo', 'Técnico Incompleto',
                                    'Secundaria Completa', 'Secundaria Incompleta',
                                    'Primaria Completa', 'Primaria Incompleta'
                                ];
                            @endphp
                            <option value="">Selecciona...</option>
                            @foreach($niveles as $nivel)
                                <option value="{{ $nivel }}" {{ $participante->nivel_educativo == $nivel ? 'selected' : '' }}>{{ $nivel }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Género</label>
                        <select class="form-select" name="genero" required>
                            <option value="">Selecciona...</option>
                            <option value="Masculino" {{ $participante->genero == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                            <option value="Femenino" {{ $participante->genero == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                            <option value="Otro" {{ $participante->genero == 'Otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Municipio</label>
                        <input type="text" class="form-control" name="municipio" value="{{ $participante->municipio }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Ciudad</label>
                        <input type="text" class="form-control" name="ciudad" value="{{ $participante->ciudad }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">¿Es afiliado?</label>
                        <select name="afiliado" id="afiliado" class="form-select" required>
                            <option value="0" {{ !$participante->afiliado ? 'selected' : '' }}>No</option>
                            <option value="1" {{ $participante->afiliado ? 'selected' : '' }}>Sí</option>
                        </select>
                    </div>

                    @if(strtolower($capacitacion->medio) === 'pago')
                    <div class="col-md-6">
                        <label class="form-label">Comprobante de Pago</label>
                        <input type="file" class="form-control" name="comprobante" accept="image/*,application/pdf">
                        @if($participante->comprobante)
                            <small class="text-muted">
                                Ya adjunto: <a href="{{ asset('storage/' . $participante->comprobante) }}" target="_blank">Ver Comprobante</a>
                            </small>
                        @endif
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Precio</label>
                        <input type="text" class="form-control" id="precio" name="precio" value="{{ $participante->precio }}" readonly>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">ISV</label>
                        <input type="text" class="form-control" id="isv" name="isv" value="{{ $participante->isv }}" readonly>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Total a Pagar</label>
                        <input type="text" class="form-control" name="total" id="total" value="{{ $participante->total }}" readonly>
                    </div>
                    @endif
                </div>

                <div class="mt-4 d-flex flex-wrap gap-2 justify-content-between">
                    <button type="submit" class="btn btn-success">💾 Guardar Cambios</button>
                    <a href="{{ route('capacitaciones.participantes', $capacitacion->id) }}" class="btn btn-secondary">🔙 Volver a Lista</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const afiliado = document.getElementById('afiliado');
        const precio = document.getElementById('precio');
        const isv = document.getElementById('isv');
        const total = document.getElementById('total');

        function calcularTotal() {
            let base = 0;
            let impuesto = 0;

            @if(strtolower($capacitacion->medio) === 'pago')
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
