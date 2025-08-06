@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4"><i class="fas fa-pen-nib"></i> Editar Plantilla Global</h2>

    <form action="{{ route('plantillas-globales.update', $plantilla->id) }}" method="POST" enctype="multipart/form-data" class="card p-4 shadow">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label><strong>Nombre de la Plantilla</strong></label>
                <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $plantilla->nombre) }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label><strong>Fecha de Emisión</strong></label>
                <input type="date" name="fecha_emision" class="form-control" value="{{ old('fecha_emision', $plantilla->fecha_emision) }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label><strong>Orientación</strong></label>
                <select name="orientacion" class="form-control" required>
                    <option value="horizontal" {{ $plantilla->orientacion == 'horizontal' ? 'selected' : '' }}>Horizontal</option>
                    <option value="vertical" {{ $plantilla->orientacion == 'vertical' ? 'selected' : '' }}>Vertical</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label><strong>Tipo de Certificado</strong></label>
                <select name="tipo_certificado" id="tipo_certificado" class="form-control" required>
                    <option value="generico" {{ $plantilla->tipo_certificado == 'generico' ? 'selected' : '' }}>Genérico</option>
                    <option value="convenio" {{ $plantilla->tipo_certificado == 'convenio' ? 'selected' : '' }}>Convenio</option>
                </select>
            </div>

            <div class="col-12 mb-3" id="titulo_convenio_div" style="{{ $plantilla->tipo_certificado == 'convenio' ? '' : 'display: none;' }}">
                <label><strong>Título (solo si es convenio)</strong></label>
                <input type="text" name="titulo_convenio" class="form-control" value="{{ old('titulo_convenio', $plantilla->titulo_convenio) }}">
            </div>

            <div class="col-md-12 mb-3">
                <label><strong>Fondo del Diploma (dejar en blanco para no cambiar)</strong></label>
                <input type="file" name="fondo" accept="image/*" class="form-control" onchange="previewImage(event, 'preview_fondo')">
                @if($plantilla->fondo)
                    <div class="mt-2">
                        <img id="preview_fondo" src="{{ asset('storage/' . $plantilla->fondo) }}" alt="Fondo del Diploma" style="max-width: 100%; height: 120px;">
                    </div>
                @else
                    <div class="mt-2">
                        <img id="preview_fondo" style="max-width: 100%; height: 120px; display:none;">
                    </div>
                @endif
            </div>

            <div class="col-md-6 mb-3">
                <label><strong>Firma 1 (izquierda)</strong></label>
                <input type="file" name="firma_1" accept="image/*" class="form-control" onchange="previewImage(event, 'preview_firma_1')">
                @if($plantilla->firma_1)
                    <div class="mt-2">
                        <img id="preview_firma_1" src="{{ asset('storage/' . $plantilla->firma_1) }}" alt="Firma 1" style="max-width: 100%; height: 80px;">
                    </div>
                @else
                    <div class="mt-2">
                        <img id="preview_firma_1" style="max-width: 100%; height: 80px; display:none;">
                    </div>
                @endif
                <label class="mt-2">Nombre del Firmante 1</label>
                <input type="text" name="nombre_firma_1" class="form-control" value="{{ old('nombre_firma_1', $plantilla->nombre_firma_1) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label><strong>Firma 2 (derecha)</strong></label>
                <input type="file" name="firma_2" accept="image/*" class="form-control" onchange="previewImage(event, 'preview_firma_2')">
                @if($plantilla->firma_2)
                    <div class="mt-2">
                        <img id="preview_firma_2" src="{{ asset('storage/' . $plantilla->firma_2) }}" alt="Firma 2" style="max-width: 100%; height: 80px;">
                    </div>
                @else
                    <div class="mt-2">
                        <img id="preview_firma_2" style="max-width: 100%; height: 80px; display:none;">
                    </div>
                @endif
                <label class="mt-2">Nombre del Firmante 2</label>
                <input type="text" name="nombre_firma_2" class="form-control" value="{{ old('nombre_firma_2', $plantilla->nombre_firma_2) }}">
            </div>
        </div>

        <div class="text-center">
            <button class="btn btn-primary"><i class="fas fa-save"></i> Actualizar Plantilla</button>
            <a href="{{ route('plantillas-globales.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<script>
    document.getElementById('tipo_certificado').addEventListener('change', function () {
        const isConvenio = this.value === 'convenio';
        document.getElementById('titulo_convenio_div').style.display = isConvenio ? 'block' : 'none';
    });
</script>
@endsection
