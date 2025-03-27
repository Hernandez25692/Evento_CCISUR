@extends('layouts.app')

@section('content')
<div class="form-container">
    <header class="form-header">
        <div class="container">
            <h1 class="form-title">
                <i class="fas fa-plus-circle me-2"></i> Nueva Formación
            </h1>
            <p class="form-subtitle">Complete los campos requeridos para registrar una nueva Formación</p>
        </div>
    </header>

    <div class="container py-4">
        <div class="card form-card shadow-sm">
            <div class="card-body">
                <form action="{{ route('capacitaciones.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    @csrf

                    <div class="form-section mb-5">
                        <div class="section-header mb-4">
                            <h5 class="section-title"><i class="fas fa-info-circle me-2"></i>Información de la Formación</h5>
                            <div class="section-divider"></div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nombre" class="form-label">Nombre*</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-book"></i></span>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                                    <div class="invalid-feedback">Ingrese el nombre de la formación.</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="impartido_por" class="form-label">Impartido por*</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                    <input type="text" class="form-control" id="impartido_por" name="impartido_por" required>
                                    <div class="invalid-feedback">Ingrese el nombre del facilitador.</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="lugar" class="form-label">Lugar*</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                    <input type="text" class="form-control" id="lugar" name="lugar" required>
                                    <div class="invalid-feedback">Ingrese el lugar.</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="fecha" class="form-label">Fecha*</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    <input type="date" class="form-control" id="fecha" name="fecha" required>
                                    <div class="invalid-feedback">Seleccione la fecha.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section mb-4">
                        <div class="section-header mb-4">
                            <h5 class="section-title"><i class="fas fa-align-left me-2"></i>Descripción</h5>
                            <div class="section-divider"></div>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Ingrese una breve descripción..."></textarea>
                        </div>
                    </div>

                    <div class="form-section mb-4">
                        <div class="section-header mb-4">
                            <h5 class="section-title"><i class="fas fa-image me-2"></i>Imagen</h5>
                            <div class="section-divider"></div>
                        </div>

                        <div class="mb-3">
                            <label for="imagen" class="form-label">Imagen de la Formación</label>
                            <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                            <div class="form-text">Formatos admitidos: JPG, PNG.</div>
                            <div class="image-preview mt-2" id="imagePreview">
                                <img src="" alt="Vista previa de la imagen" class="img-thumbnail d-none" id="previewImage">
                            </div>
                        </div>
                    </div>

                    <div class="form-actions d-flex justify-content-between pt-3 border-top">
                        <a href="{{ route('capacitaciones.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
    .form-header {
        background: linear-gradient(135deg, #4361ee, #3f37c9);
        color: white;
        padding: 2rem 0;
        margin-bottom: 2rem;
        text-align: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .form-title {
        font-size: 2rem;
        font-weight: bold;
    }

    .form-subtitle {
        font-weight: 300;
        font-size: 1rem;
    }

    .form-card {
        border-radius: 10px;
    }

    .form-section {
        padding: 1rem;
        background: #fff;
        border-radius: 10px;
        margin-bottom: 2rem;
        box-shadow: 0 1px 5px rgba(0,0,0,0.05);
    }

    .section-title {
        color: #4361ee;
        font-weight: 600;
    }

    .section-divider {
        height: 2px;
        background: #e0e0e0;
        margin-top: 4px;
    }

    .form-actions .btn {
        padding: 0.7rem 1.5rem;
    }

    .image-preview {
        width: 100%;
        min-height: 150px;
        display: flex;
        justify-content: center;
        align-items: center;
        border: 1px solid #ccc;
        border-radius: 10px;
        overflow: hidden;
    }

    .img-thumbnail {
        max-height: 200px;
        object-fit: contain;
    }
</style>

<script>
    // Vista previa de la imagen
    document.getElementById('imagen').addEventListener('change', function (e) {
        const previewImage = document.getElementById('previewImage');
        const file = e.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function (event) {
                previewImage.src = event.target.result;
                previewImage.classList.remove('d-none');
            }
            reader.readAsDataURL(file);
        } else {
            previewImage.src = '';
            previewImage.classList.add('d-none');
        }
    });

    // Validación Bootstrap
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
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
