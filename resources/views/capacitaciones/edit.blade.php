@extends('layouts.app')

@section('content')
    <div class="form-container">
        <header class="form-header">
            <div class="container">
                <h1 class="form-title">
                    <i class="fas fa-edit me-2"></i>Editar Formación
                </h1>
                <p class="form-subtitle">Modifica los datos de la capacitación seleccionada</p>
            </div>
        </header>

        <div class="container py-4">
            <div class="card form-card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('capacitaciones.update', $capacitacion->id) }}" method="POST"
                        enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        <!-- Información básica -->
                        <div class="form-section mb-5">
                            <div class="section-header mb-4">
                                <h5 class="section-title"><i class="fas fa-info-circle me-2"></i>Información de la Formación
                                </h5>
                                <div class="section-divider"></div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="nombre" class="form-label">Nombre*</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-book"></i></span>
                                        <input type="text" class="form-control" id="nombre" name="nombre"
                                            value="{{ $capacitacion->nombre }}" required>
                                        <div class="invalid-feedback">Ingrese el nombre de la formación.</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="impartido_por" class="form-label">Impartido por*</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                        <input type="text" class="form-control" id="impartido_por" name="impartido_por"
                                            value="{{ $capacitacion->impartido_por }}" required>
                                        <div class="invalid-feedback">Ingrese el nombre del facilitador.</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="lugar" class="form-label">Lugar*</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        <input type="text" class="form-control" id="lugar" name="lugar"
                                            value="{{ $capacitacion->lugar }}" required>
                                        <div class="invalid-feedback">Ingrese el lugar.</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="fecha" class="form-label">Fecha*</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        <input type="date" class="form-control" id="fecha" name="fecha"
                                            value="{{ $capacitacion->fecha }}" required>
                                        <div class="invalid-feedback">Seleccione la fecha.</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="tipo_formacion" class="form-label">Tipo de Formación</label>
                                    <select class="form-select" name="tipo_formacion">
                                        <option value="">Seleccione una opción</option>
                                        @foreach (['Webinar', 'Charla', 'Taller', 'Seminario', 'Capacitación', 'Diplomado', 'Charla informativa'] as $tipo)
                                            <option value="{{ $tipo }}"
                                                {{ $capacitacion->tipo_formacion == $tipo ? 'selected' : '' }}>
                                                {{ $tipo }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="duracion" class="form-label">Duración (horas)</label>
                                    <input type="text" class="form-control" name="duracion"
                                        value="{{ $capacitacion->duracion }}">
                                </div>

                                <div class="col-md-6">
                                    <label for="forma" class="form-label">Forma de impartir</label>
                                    <select class="form-select" name="forma">
                                        <option value="">Seleccione una opción</option>
                                        <option value="Presencial"
                                            {{ $capacitacion->forma == 'Presencial' ? 'selected' : '' }}>Presencial</option>
                                        <option value="Virtual" {{ $capacitacion->forma == 'Virtual' ? 'selected' : '' }}>
                                            Virtual</option>
                                        <option value="Híbrida" {{ $capacitacion->forma == 'Híbrida' ? 'selected' : '' }}>
                                            Híbrida</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="cupos" class="form-label">Cupos</label>
                                    <select class="form-select" name="cupos" id="cupos">
                                        <option value="ilimitado"
                                            {{ $capacitacion->cupos == 'ilimitado' ? 'selected' : '' }}>Ilimitado</option>
                                        <option value="limitado"
                                            {{ $capacitacion->cupos == 'limitado' ? 'selected' : '' }}>Limitado</option>
                                    </select>
                                </div>

                                <div class="col-md-6" id="limite_participantes_container"
                                    style="display: {{ $capacitacion->cupos == 'limitado' ? 'block' : 'none' }}">
                                    <label for="limite_participantes" class="form-label">Límite de Participantes</label>
                                    <input type="number" class="form-control" name="limite_participantes"
                                        value="{{ $capacitacion->limite_participantes }}">
                                </div>

                                <div class="col-md-6">
                                    <label for="medio" class="form-label">Medio</label>
                                    <select class="form-select" name="medio" id="medio">
                                        <option value="gratis" {{ $capacitacion->medio == 'gratis' ? 'selected' : '' }}>
                                            Gratis</option>
                                        <option value="pago" {{ $capacitacion->medio == 'pago' ? 'selected' : '' }}>De
                                            Paga</option>
                                    </select>
                                </div>

                                <div id="precios_pago"
                                    style="display: {{ $capacitacion->medio == 'pago' ? 'block' : 'none' }}">
                                    <div class="row mt-3">
                                        <div class="col-md-3">
                                            <label>Precio Afiliado</label>
                                            <input type="number" step="0.01" class="form-control"
                                                name="precio_afiliado" value="{{ $capacitacion->precio_afiliado }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label>ISV Afiliado</label>
                                            <input type="number" step="0.01" class="form-control"
                                                name="isv_afiliado" value="{{ $capacitacion->isv_afiliado }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Precio No Afiliado</label>
                                            <input type="number" step="0.01" class="form-control"
                                                name="precio_no_afiliado"
                                                value="{{ $capacitacion->precio_no_afiliado }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label>ISV No Afiliado</label>
                                            <input type="number" step="0.01" class="form-control"
                                                name="isv_no_afiliado" value="{{ $capacitacion->isv_no_afiliado }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div class="form-section mb-5">
                            <div class="section-header mb-4">
                                <h5 class="section-title"><i class="fas fa-align-left me-2"></i>Descripción</h5>
                                <div class="section-divider"></div>
                            </div>

                            <div class="mb-3">
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="4"
                                    placeholder="Descripción opcional...">{{ $capacitacion->descripcion }}</textarea>
                            </div>
                        </div>

                        <!-- Imagen -->
                        <div class="form-section mb-4">
                            <div class="section-header mb-4">
                                <h5 class="section-title"><i class="fas fa-image me-2"></i>Imagen</h5>
                                <div class="section-divider"></div>
                            </div>

                            @if ($capacitacion->imagen)
                                <div class="mb-3">
                                    <p>Imagen actual:</p>
                                    <img src="{{ asset('storage/' . $capacitacion->imagen) }}" class="img-thumbnail"
                                        style="max-width: 250px;">
                                </div>
                            @endif

                            <div class="mb-3">
                                <label for="imagen" class="form-label">Cambiar Imagen</label>
                                <input type="file" class="form-control" id="imagen" name="imagen"
                                    accept="image/*">
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="form-actions d-flex justify-content-between pt-4 border-top">
                            <a href="{{ route('capacitaciones.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <script>
        // Mostrar límite de participantes si cupos es limitado
        document.getElementById('cupos').addEventListener('change', function() {
            const limite = document.getElementById('limite_participantes_container');
            limite.style.display = this.value === 'limitado' ? 'block' : 'none';
        });

        // Mostrar precios si el medio es de paga
        document.getElementById('medio').addEventListener('change', function() {
            document.getElementById('precios_pago').style.display = this.value === 'pago' ? 'block' : 'none';
        });

        // Validación Bootstrap
        (function() {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            });
        })();
    </script>
@endsection
