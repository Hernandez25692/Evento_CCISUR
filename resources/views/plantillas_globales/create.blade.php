@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4"><i class="fas fa-certificate"></i> Crear Plantilla Global</h2>

        <form action="{{ route('plantillas-globales.store') }}" method="POST" enctype="multipart/form-data"
            class="card p-4 shadow">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label><strong>Nombre de la Plantilla</strong></label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label><strong>Fecha de Emisión</strong></label>
                    <input type="date" name="fecha_emision" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label><strong>Orientación</strong></label>
                    <select name="orientacion" class="form-control" required>
                        <option value="horizontal">Horizontal</option>
                        <option value="vertical">Vertical</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label><strong>Tipo de Certificado</strong></label>
                    <select name="tipo_certificado" id="tipo_certificado" class="form-control" required>
                        <option value="generico">Genérico</option>
                        <option value="convenio">Convenio</option>
                    </select>
                </div>

                <div class="col-12 mb-3" id="titulo_convenio_div" style="display: none;">
                    <label><strong>Título (solo si es convenio)</strong></label>
                    <input type="text" name="titulo_convenio" class="form-control">
                </div>

                <div class="col-md-12 mb-3">
                    <label><strong>Fondo del Diploma</strong></label>
                    <input type="file" name="fondo" accept="image/*" class="form-control" required onchange="previewFondo(event)">
                    <div class="mt-2">
                        <img id="fondoPreview" src="#" alt="Previsualización" style="max-width: 300px; max-height: 150px; display: none;" />
                    </div>
                    </div>
                    <script>
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
                    </script>

                <div class="col-md-6 mb-3">
                    <label><strong>Firma 1 (izquierda)</strong></label>
                    <input type="file" name="firma_1" accept="image/*" class="form-control" onchange="previewFirma1(event)">
                    <div class="mt-2">
                        <img id="firma1Preview" src="#" alt="Previsualización Firma 1" style="max-width: 200px; max-height: 100px; display: none;" />
                    </div>
                    <script>
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
                    </script>
                    <label class="mt-2">Nombre del Firmante 1</label>
                    <input type="text" name="nombre_firma_1" class="form-control" placeholder="Ej. Ing. Juan Pérez">
                </div>

                <div class="col-md-6 mb-3">
                    <label><strong>Firma 2 (derecha)</strong></label>
                    <input type="file" name="firma_2" accept="image/*" class="form-control" onchange="previewFirma2(event)">
                    <div class="mt-2">
                        <img id="firma2Preview" src="#" alt="Previsualización Firma 2" style="max-width: 200px; max-height: 100px; display: none;" />
                    </div>
                    <script>
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
                    <label class="mt-2">Nombre del Firmante 2</label>
                    <input type="text" name="nombre_firma_2" class="form-control" placeholder="Ej. Lic. Ana López">
                </div>
            </div>

            <div class="text-center">
                <button class="btn btn-success"><i class="fas fa-save"></i> Guardar Plantilla</button>
                <a href="{{ route('plantillas-globales.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('tipo_certificado').addEventListener('change', function() {
            const isConvenio = this.value === 'convenio';
            document.getElementById('titulo_convenio_div').style.display = isConvenio ? 'block' : 'none';
        });
    </script>
@endsection
