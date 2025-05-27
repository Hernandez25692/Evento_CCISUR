@extends('layouts.app')

@section('content')
    <div class="training-edit-container">
        <!-- Header Section -->
        <header class="edit-header">
            <div class="header-content">
                <div class="header-icon">
                    <i class="fas fa-edit"></i>
                </div>
                <div class="header-text">
                    <h1>Editar Formación</h1>
                    <p>Modifica los datos de la capacitación seleccionada</p>
                </div>
            </div>
        </header>

        <div class="edit-content">
            <div class="edit-card">
                <form action="{{ route('capacitaciones.update', $capacitacion->id) }}" method="POST"
                    enctype="multipart/form-data" class="edit-form" novalidate>
                    @csrf
                    @method('PUT')

                    <!-- Basic Information Section -->
                    <section class="form-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <h2>Información de la Formación</h2>
                        </div>

                        <div class="form-grid">
                            <!-- Name Field -->
                            <div class="form-group">
                                <label for="nombre">
                                    <i class="fas fa-book"></i> Nombre*
                                </label>
                                <input type="text" id="nombre" name="nombre" value="{{ $capacitacion->nombre }}"
                                    required>
                                <div class="validation-message">Ingrese el nombre de la formación.</div>
                            </div>

                            <!-- Instructor Field -->
                            <div class="form-group">
                                <label for="impartido_por">
                                    <i class="fas fa-user-tie"></i> Impartido por*
                                </label>
                                <input type="text" id="impartido_por" name="impartido_por"
                                    value="{{ $capacitacion->impartido_por }}" required>
                                <div class="validation-message">Ingrese el nombre del facilitador.</div>
                            </div>

                            <!-- Location Field -->
                            <div class="form-group">
                                <label for="lugar">
                                    <i class="fas fa-map-marker-alt"></i> Lugar*
                                </label>
                                <input type="text" id="lugar" name="lugar" value="{{ $capacitacion->lugar }}"
                                    required>
                                <div class="validation-message">Ingrese el lugar.</div>
                            </div>

                            <!-- Date Field -->
                            <div class="form-group">
                                <label for="fecha">
                                    <i class="fas fa-calendar-alt"></i> Fecha*
                                </label>
                                <input type="date" id="fecha" name="fecha" value="{{ $capacitacion->fecha }}"
                                    required>
                                <div class="validation-message">Seleccione la fecha.</div>
                            </div>

                            <!-- Training Type -->
                            <div class="form-group">
                                <label for="tipo_formacion">
                                    <i class="fas fa-tags"></i> Tipo de Formación
                                </label>
                                <select id="tipo_formacion" name="tipo_formacion">
                                    <option value="">Seleccione una opción</option>
                                    @foreach (['Webinar', 'Charla', 'Taller', 'Seminario', 'Capacitación', 'Diplomado', 'Charla informativa'] as $tipo)
                                        <option value="{{ $tipo }}"
                                            {{ $capacitacion->tipo_formacion == $tipo ? 'selected' : '' }}>
                                            {{ $tipo }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Duration -->
                            <div class="form-group">
                                <label for="duracion">
                                    <i class="fas fa-clock"></i> Duración (horas)
                                </label>
                                <input type="text" id="duracion" name="duracion" value="{{ $capacitacion->duracion }}">
                            </div>
                            <div class="form-group">
                                <label for="hora_inicio"><i class="fas fa-clock"></i> Hora de Inicio</label>
                                <input type="time" id="hora_inicio" name="hora_inicio"
                                    value="{{ $capacitacion->hora_inicio }}">
                            </div>

                            <div class="form-group">
                                <label for="hora_fin"><i class="fas fa-clock"></i> Hora de Finalización</label>
                                <input type="time" id="hora_fin" name="hora_fin" value="{{ $capacitacion->hora_fin }}">
                            </div>

                            <!-- Delivery Method -->
                            <div class="form-group">
                                <label for="forma">
                                    <i class="fas fa-laptop-house"></i> Forma de impartir
                                </label>
                                <select id="forma" name="forma">
                                    <option value="">Seleccione una opción</option>
                                    <option value="Presencial"
                                        {{ $capacitacion->forma == 'Presencial' ? 'selected' : '' }}>Presencial</option>
                                    <option value="Virtual" {{ $capacitacion->forma == 'Virtual' ? 'selected' : '' }}>
                                        Virtual</option>
                                    <option value="Híbrida" {{ $capacitacion->forma == 'Híbrida' ? 'selected' : '' }}>
                                        Híbrida</option>
                                </select>
                            </div>

                            <!-- Capacity -->
                            <div class="form-group">
                                <label for="cupos">
                                    <i class="fas fa-users"></i> Cupos
                                </label>
                                <select id="cupos" name="cupos">
                                    <option value="ilimitado" {{ $capacitacion->cupos == 'ilimitado' ? 'selected' : '' }}>
                                        Ilimitado</option>
                                    <option value="limitado" {{ $capacitacion->cupos == 'limitado' ? 'selected' : '' }}>
                                        Limitado</option>
                                </select>
                            </div>

                            <!-- Participant Limit (conditional) -->
                            <div class="form-group" id="limite_participantes_container"
                                style="display: {{ $capacitacion->cupos == 'limitado' ? 'flex' : 'none' }}">
                                <label for="limite_participantes">
                                    <i class="fas fa-user-slash"></i> Límite de Participantes
                                </label>
                                <input type="number" id="limite_participantes" name="limite_participantes"
                                    value="{{ $capacitacion->limite_participantes }}">
                            </div>

                            <!-- Payment Method -->
                            <div class="form-group">
                                <label for="medio">
                                    <i class="fas fa-money-bill-wave"></i> Medio
                                </label>
                                <select id="medio" name="medio">
                                    <option value="gratis" {{ $capacitacion->medio == 'gratis' ? 'selected' : '' }}>Gratis
                                    </option>
                                    <option value="pago" {{ $capacitacion->medio == 'pago' ? 'selected' : '' }}>De Paga
                                    </option>
                                </select>
                            </div>

                            <!-- Pricing Section (conditional) -->
                            <div id="precios_pago"
                                style="display: {{ $capacitacion->medio == 'pago' ? 'grid' : 'none' }}"
                                class="pricing-grid">
                                <div class="form-group">
                                    <label>Precio Afiliado</label>
                                    <input type="number" step="0.01" name="precio_afiliado"
                                        value="{{ $capacitacion->precio_afiliado }}">
                                </div>
                                <div class="form-group">
                                    <label>ISV Afiliado</label>
                                    <input type="number" step="0.01" name="isv_afiliado"
                                        value="{{ $capacitacion->isv_afiliado }}">
                                </div>
                                <div class="form-group">
                                    <label>Precio No Afiliado</label>
                                    <input type="number" step="0.01" name="precio_no_afiliado"
                                        value="{{ $capacitacion->precio_no_afiliado }}">
                                </div>
                                <div class="form-group">
                                    <label>ISV No Afiliado</label>
                                    <input type="number" step="0.01" name="isv_no_afiliado"
                                        value="{{ $capacitacion->isv_no_afiliado }}">
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Description Section -->
                    <section class="form-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-align-left"></i>
                            </div>
                            <h2>Descripción</h2>
                        </div>
                        <div class="form-group full-width">
                            <textarea id="descripcion" name="descripcion" placeholder="Descripción opcional...">{{ $capacitacion->descripcion }}</textarea>
                        </div>
                    </section>

                    <!-- Image Section -->
                    <section class="form-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-image"></i>
                            </div>
                            <h2>Imagen</h2>
                        </div>

                        @if ($capacitacion->imagen)
                            <div class="current-image">
                                <p>Imagen actual:</p>
                                <img src="{{ asset('storage/' . $capacitacion->imagen) }}"
                                    alt="Imagen actual de la capacitación">
                            </div>
                        @endif

                        <div class="form-group full-width">
                            <label for="imagen">Cambiar Imagen</label>
                            <div class="file-upload">
                                <input type="file" id="imagen" name="imagen" accept="image/*">
                                <label for="imagen" class="upload-button">
                                    <i class="fas fa-cloud-upload-alt"></i> Seleccionar archivo
                                </label>
                            </div>
                        </div>
                    </section>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <a href="{{ route('capacitaciones.index') }}" class="cancel-button">
                            <i class="fas fa-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="submit-button">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a0ca3;
            --primary-light: #e6f0ff;
            --secondary: #4cc9f0;
            --success: #38b000;
            --danger: #ef233c;
            --warning: #ff9e00;
            --light: #f8f9fa;
            --dark: #212529;
            --text: #2b2d42;
            --text-light: #8d99ae;
            --border-radius: 12px;
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.15);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        /* Base Styles */
        .training-edit-container {
            min-height: 100vh;
            background-color: #f8fafc;
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
            color: var(--text);
        }

        /* Header Styles */
        .edit-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 2rem 1rem;
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .header-icon {
            font-size: 2.5rem;
            color: rgba(255, 255, 255, 0.9);
        }

        .header-text h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .header-text p {
            opacity: 0.9;
            margin: 0;
        }

        /* Main Content */
        .edit-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .edit-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-md);
            overflow: hidden;
        }

        .edit-form {
            padding: 2rem;
        }

        /* Form Sections */
        .form-section {
            margin-bottom: 2.5rem;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .section-icon {
            width: 40px;
            height: 40px;
            background: var(--primary-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
        }

        .section-header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
        }

        /* Form Grid */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .pricing-grid {
            grid-column: 1 / -1;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-top: 1rem;
            padding: 1.5rem;
            background: var(--primary-light);
            border-radius: var(--border-radius);
        }

        /* Form Groups */
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-group label {
            font-weight: 500;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-group label i {
            width: 20px;
            text-align: center;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 0.75rem 1rem;
            border: 1px solid #e0e0e0;
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        .validation-message {
            color: var(--danger);
            font-size: 0.85rem;
            display: none;
        }

        input:invalid:not(:placeholder-shown)+.validation-message,
        select:invalid:not(:placeholder-shown)+.validation-message {
            display: block;
        }

        /* File Upload */
        .file-upload {
            position: relative;
        }

        .file-upload input[type="file"] {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .upload-button {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1rem;
            background: var(--primary-light);
            color: var(--primary);
            border-radius: var(--border-radius);
            border: 1px dashed var(--primary);
            transition: var(--transition);
        }

        .upload-button:hover {
            background: rgba(67, 97, 238, 0.1);
        }

        /* Current Image */
        .current-image {
            margin-bottom: 1.5rem;
        }

        .current-image p {
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .current-image img {
            max-width: 300px;
            height: auto;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            justify-content: space-between;
            padding-top: 2rem;
            margin-top: 2rem;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        .cancel-button,
        .submit-button {
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: var(--transition);
            border: none;
            cursor: pointer;
        }

        .cancel-button {
            background: transparent;
            color: var(--text-light);
            border: 1px solid #e0e0e0;
        }

        .cancel-button:hover {
            background: #f5f5f5;
            color: var(--text);
        }

        .submit-button {
            background: var(--primary);
            color: white;
        }

        .submit-button:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-sm);
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .header-content {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .header-icon {
                margin-bottom: 0.5rem;
            }
        }

        @media (max-width: 768px) {
            .edit-form {
                padding: 1.5rem;
            }

            .form-actions {
                flex-direction: column-reverse;
                gap: 1rem;
            }

            .cancel-button,
            .submit-button {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 576px) {
            .header-text h1 {
                font-size: 1.5rem;
            }

            .section-header h2 {
                font-size: 1.25rem;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .pricing-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle participant limit field
            const cuposSelect = document.getElementById('cupos');
            const limiteContainer = document.getElementById('limite_participantes_container');

            cuposSelect.addEventListener('change', function() {
                limiteContainer.style.display = this.value === 'limitado' ? 'flex' : 'none';
            });

            // Toggle pricing fields
            const medioSelect = document.getElementById('medio');
            const preciosPago = document.getElementById('precios_pago');

            medioSelect.addEventListener('change', function() {
                preciosPago.style.display = this.value === 'pago' ? 'grid' : 'none';
            });

            // Form validation
            const form = document.querySelector('.edit-form');
            const requiredFields = form.querySelectorAll('[required]');

            form.addEventListener('submit', function(e) {
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value) {
                        isValid = false;
                        field.classList.add('invalid');
                    } else {
                        field.classList.remove('invalid');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    // Scroll to first invalid field
                    const firstInvalid = form.querySelector('.invalid');
                    if (firstInvalid) {
                        firstInvalid.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                }
            });

            // File upload display
            const fileInput = document.querySelector('input[type="file"]');
            const uploadButton = document.querySelector('.upload-button');

            fileInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    uploadButton.innerHTML = `<i class="fas fa-check"></i> ${this.files[0].name}`;
                } else {
                    uploadButton.innerHTML = '<i class="fas fa-cloud-upload-alt"></i> Seleccionar archivo';
                }
            });
        });
    </script>
@endsection
