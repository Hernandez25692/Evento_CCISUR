@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white d-flex align-items-center">
                        <i class="fas fa-certificate fa-2x me-2"></i>
                        <h2 class="mb-0 fs-4">Crear Plantilla Global</h2>
                    </div>
                    <div class="card-body bg-light">
                        <form action="{{ route('plantillas-globales.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Nombre de la Plantilla</label>
                                    <input type="text" name="nombre" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Fecha de Emisión</label>
                                    <input type="date" name="fecha_emision" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Orientación</label>
                                    <select name="orientacion" class="form-select" required>
                                        <option value="horizontal">Horizontal</option>
                                        <option value="vertical">Vertical</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Tipo de Certificado</label>
                                    <select name="tipo_certificado" id="tipo_certificado" class="form-select" required>
                                        <option value="generico">Genérico</option>
                                        <option value="convenio">Convenio</option>
                                    </select>
                                </div>
                                <div class="col-12" id="titulo_convenio_div" style="display: none;">
                                    <label class="form-label fw-bold">Título (solo si es convenio)</label>
                                    <input type="text" name="titulo_convenio" class="form-control">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold">Fondo del Diploma</label>
                                    <input type="file" name="fondo" accept="image/*" class="form-control" required onchange="previewFondo(event)">
                                    <div class="mt-2">
                                        <img id="fondoPreview" src="#" alt="Previsualización" class="img-thumbnail shadow-sm" style="max-width: 350px; max-height: 180px; display: none;" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Firma 1 (izquierda)</label>
                                    <input type="file" name="firma_1" accept="image/*" class="form-control" onchange="previewFirma1(event)">
                                    <div class="mt-2">
                                        <img id="firma1Preview" src="#" alt="Previsualización Firma 1" class="img-thumbnail shadow-sm" style="max-width: 220px; max-height: 110px; display: none;" />
                                    </div>
                                    <label class="form-label mt-2">Nombre del Firmante 1</label>
                                    <input type="text" name="nombre_firma_1" class="form-control" placeholder="Ej. Ing. Juan Pérez">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Firma 2 (derecha)</label>
                                    <input type="file" name="firma_2" accept="image/*" class="form-control" onchange="previewFirma2(event)">
                                    <div class="mt-2">
                                        <img id="firma2Preview" src="#" alt="Previsualización Firma 2" class="img-thumbnail shadow-sm" style="max-width: 220px; max-height: 110px; display: none;" />
                                    </div>
                                    <label class="form-label mt-2">Nombre del Firmante 2</label>
                                    <input type="text" name="nombre_firma_2" class="form-control" placeholder="Ej. Lic. Ana López">
                                </div>
                            </div>
                            <div class="text-center mt-4">
                                <button class="btn btn-success px-4 py-2"><i class="fas fa-save"></i> Guardar Plantilla</button>
                                <a href="{{ route('plantillas-globales.index') }}" class="btn btn-secondary px-4 py-2 ms-2">Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        body {
            background: linear-gradient(135deg, #e0e7ff 0%, #f8fafc 100%);
        }
        .card {
            border-radius: 1.2rem;
        }
        .card-header {
            border-top-left-radius: 1.2rem;
            border-top-right-radius: 1.2rem;
        }
        .form-label {
            font-size: 1rem;
        }
        .btn-success {
            font-size: 1.1rem;
        }
    </style>

    <script>
        document.getElementById('tipo_certificado').addEventListener('change', function() {
            const isConvenio = this.value === 'convenio';
            document.getElementById('titulo_convenio_div').style.display = isConvenio ? 'block' : 'none';
        });

        function previewFondo(event) {
            const input = event.target;
            const preview = document.getElementById('fondoPreview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = '#';
                preview.style.display = 'none';
            }
        }
        function previewFirma1(event) {
            const input = event.target;
            const preview = document.getElementById('firma1Preview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = '#';
                preview.style.display = 'none';
            }
        }
        function previewFirma2(event) {
            const input = event.target;
            const preview = document.getElementById('firma2Preview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = '#';
                preview.style.display = 'none';
            }
        }
    </script>
@endsection
