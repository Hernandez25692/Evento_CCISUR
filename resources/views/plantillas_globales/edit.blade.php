@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #e0e7ff 0%, #f3f4f6 100%);
    }
    .custom-card {
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        border: none;
    }
    .custom-label {
        font-weight: 600;
        color: #374151;
        letter-spacing: 0.5px;
    }
    .custom-input, .custom-select {
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        background: #f9fafb;
        transition: border-color 0.2s;
    }
    .custom-input:focus, .custom-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 2px #6366f133;
    }
    .custom-btn-primary {
        background: linear-gradient(90deg, #6366f1 0%, #3b82f6 100%);
        border: none;
        color: #fff;
        font-weight: 600;
        border-radius: 8px;
        padding: 10px 28px;
        transition: background 0.2s;
    }
    .custom-btn-primary:hover {
        background: linear-gradient(90deg, #3b82f6 0%, #6366f1 100%);
    }
    .custom-btn-secondary {
        background: #f3f4f6;
        color: #6366f1;
        border: none;
        border-radius: 8px;
        padding: 10px 28px;
        font-weight: 600;
        margin-left: 10px;
        transition: background 0.2s;
    }
    .custom-btn-secondary:hover {
        background: #e0e7ff;
        color: #3b82f6;
    }
    .img-preview {
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background: #f9fafb;
        object-fit: contain;
        box-shadow: 0 2px 8px 0 rgba(99, 102, 241, 0.07);
    }
    .form-section-title {
        font-size: 1.1rem;
        color: #6366f1;
        margin-bottom: 0.5rem;
        font-weight: 700;
        letter-spacing: 1px;
    }
</style>
<div class="container py-4">
    <h2 class="mb-4 text-center" style="font-weight:700; color:#3b82f6;">
        <i class="fas fa-pen-nib"></i> Editar Plantilla Global
    </h2>

    <form action="{{ route('plantillas-globales.update', $plantilla->id) }}" method="POST" enctype="multipart/form-data" class="custom-card p-4 mx-auto" style="max-width: 900px;">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6 mb-4">
                <label class="custom-label">Nombre de la Plantilla</label>
                <input type="text" name="nombre" class="form-control custom-input" value="{{ old('nombre', $plantilla->nombre) }}" required>
            </div>

            <div class="col-md-6 mb-4">
                <label class="custom-label">Fecha de Emisión</label>
                <input type="date" name="fecha_emision" class="form-control custom-input" value="{{ old('fecha_emision', $plantilla->fecha_emision) }}" required>
            </div>

            <div class="col-md-6 mb-4">
                <label class="custom-label">Orientación</label>
                <select name="orientacion" class="form-control custom-select" required>
                    <option value="horizontal" {{ $plantilla->orientacion == 'horizontal' ? 'selected' : '' }}>Horizontal</option>
                    <option value="vertical" {{ $plantilla->orientacion == 'vertical' ? 'selected' : '' }}>Vertical</option>
                </select>
            </div>

            <div class="col-md-6 mb-4">
                <label class="custom-label">Tipo de Certificado</label>
                <select name="tipo_certificado" id="tipo_certificado" class="form-control custom-select" required>
                    <option value="generico" {{ $plantilla->tipo_certificado == 'generico' ? 'selected' : '' }}>Genérico</option>
                    <option value="convenio" {{ $plantilla->tipo_certificado == 'convenio' ? 'selected' : '' }}>Convenio</option>
                </select>
            </div>

            <div class="col-12 mb-4" id="titulo_convenio_div" style="{{ $plantilla->tipo_certificado == 'convenio' ? '' : 'display: none;' }}">
                <label class="custom-label">Título (solo si es convenio)</label>
                <input type="text" name="titulo_convenio" class="form-control custom-input" value="{{ old('titulo_convenio', $plantilla->titulo_convenio) }}">
            </div>

            <div class="col-md-12 mb-4">
                <div class="form-section-title">Fondo del Diploma</div>
                <label class="custom-label">Dejar en blanco para no cambiar</label>
                <input type="file" name="fondo" accept="image/*" class="form-control custom-input" onchange="previewImage(event, 'preview_fondo')">
                <div class="mt-2">
                    <img id="preview_fondo"
                        src="{{ $plantilla->fondo ? asset('storage/' . $plantilla->fondo) : '' }}"
                        alt="Fondo del Diploma"
                        class="img-preview"
                        style="max-width: 100%; height: 120px; {{ $plantilla->fondo ? '' : 'display:none;' }}">
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="form-section-title">Firma 1 (izquierda)</div>
                <input type="file" name="firma_1" accept="image/*" class="form-control custom-input" onchange="previewImage(event, 'preview_firma_1')">
                <div class="mt-2">
                    <img id="preview_firma_1"
                        src="{{ $plantilla->firma_1 ? asset('storage/' . $plantilla->firma_1) : '' }}"
                        alt="Firma 1"
                        class="img-preview"
                        style="max-width: 100%; height: 80px; {{ $plantilla->firma_1 ? '' : 'display:none;' }}">
                </div>
                <label class="custom-label mt-2">Nombre del Firmante 1</label>
                <input type="text" name="nombre_firma_1" class="form-control custom-input" value="{{ old('nombre_firma_1', $plantilla->nombre_firma_1) }}">
            </div>

            <div class="col-md-6 mb-4">
                <div class="form-section-title">Firma 2 (derecha)</div>
                <input type="file" name="firma_2" accept="image/*" class="form-control custom-input" onchange="previewImage(event, 'preview_firma_2')">
                <div class="mt-2">
                    <img id="preview_firma_2"
                        src="{{ $plantilla->firma_2 ? asset('storage/' . $plantilla->firma_2) : '' }}"
                        alt="Firma 2"
                        class="img-preview"
                        style="max-width: 100%; height: 80px; {{ $plantilla->firma_2 ? '' : 'display:none;' }}">
                </div>
                <label class="custom-label mt-2">Nombre del Firmante 2</label>
                <input type="text" name="nombre_firma_2" class="form-control custom-input" value="{{ old('nombre_firma_2', $plantilla->nombre_firma_2) }}">
            </div>
        </div>

        <div class="text-center mt-4">
            <button class="custom-btn-primary"><i class="fas fa-save"></i> Actualizar Plantilla</button>
            <a href="{{ route('plantillas-globales.index') }}" class="custom-btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<script>
    document.getElementById('tipo_certificado').addEventListener('change', function () {
        const isConvenio = this.value === 'convenio';
        document.getElementById('titulo_convenio_div').style.display = isConvenio ? 'block' : 'none';
    });

    function previewImage(event, id) {
        const input = event.target;
        const reader = new FileReader();
        reader.onload = function(){
            const img = document.getElementById(id);
            img.src = reader.result;
            img.style.display = 'block';
        };
        if(input.files && input.files[0]) {
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
