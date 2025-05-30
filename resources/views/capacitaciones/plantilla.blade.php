@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/fonts-visby.css') }}">

    <div class="diploma-template-container">
        <!-- Encabezado con efecto gradiente -->
        <div class="template-header">
            <div class="header-content">
                <h2>
                    <i class="fas fa-certificate"></i>
                    Plantilla para: {{ $capacitacion->nombre }}
                </h2>
                <p>Configuración de diplomas certificados</p>
            </div>
            <div class="header-wave">
                <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                    <path
                        d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z"
                        opacity=".25" fill="#4361ee"></path>
                    <path
                        d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z"
                        opacity=".5" fill="#4361ee"></path>
                    <path
                        d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z"
                        fill="#4361ee"></path>
                </svg>
            </div>
        </div>

        <!-- Contenedor principal -->
        <div class="template-content">
            <!-- Mensajes de alerta -->
            @if (session('success'))
                <div class="alert-message success">
                    <div class="icon"><i class="fas fa-check-circle"></i></div>
                    <div class="text">{{ session('success') }}</div>
                    <div class="close-btn"><i class="fas fa-times"></i></div>
                </div>
            @endif
            @if (session('error'))
                <div class="alert-message error">
                    <div class="icon"><i class="fas fa-exclamation-circle"></i></div>
                    <div class="text">{{ session('error') }}</div>
                    <div class="close-btn"><i class="fas fa-times"></i></div>
                </div>
            @endif

            <!-- Tarjeta de formulario -->
            <div class="form-card">
                <div class="card-header">
                    <h3><i class="fas fa-cog"></i> Configuración de la Plantilla</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('capacitaciones.plantilla.store', $capacitacion->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-grid">
                            <!-- Firma 1 -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-signature"></i> Firma 1 (izquierda)
                                </label>
                                <div class="file-upload">
                                    <input type="file" name="firma_1" id="firma_1" accept="image/*">
                                    <label for="firma_1" class="upload-label">
                                        <div class="upload-content">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <span>Seleccionar archivo</span>
                                        </div>
                                    </label>
                                    <img id="preview_firma_1" src="" alt="Vista previa Firma 1" class="d-none"
                                        style="max-height: 100px; margin-top: 10px;">
                                </div>
                                <!-- Nombre Firma 1 -->
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-user-edit"></i> Nombre de quien firma (izquierda)
                                    </label>
                                    <input type="text" name="nombre_firma_1" class="form-control"
                                        placeholder="Ej. Ing. Pedro Martínez"
                                        value="{{ old('nombre_firma_1', $plantilla->nombre_firma_1 ?? '') }}">
                                </div>
                            </div>


                            <!-- Firma 2 -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-signature"></i> Firma 2 (derecha)
                                </label>
                                <div class="file-upload">
                                    <input type="file" name="firma_2" id="firma_2" accept="image/*">
                                    <label for="firma_2" class="upload-label">
                                        <div class="upload-content">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <span>Seleccionar archivo</span>
                                        </div>
                                    </label>
                                    <img id="preview_firma_2" src="" alt="Vista previa Firma 2" class="d-none"
                                        style="max-height: 100px; margin-top: 10px;">
                                </div>
                                <!-- Nombre Firma 2 -->
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-user-edit"></i> Nombre de quien firma (derecha)
                                    </label>
                                    <input type="text" name="nombre_firma_2" class="form-control"
                                        placeholder="Ej. Lic. Ana López"
                                        value="{{ old('nombre_firma_2', $plantilla->nombre_firma_2 ?? '') }}">
                                </div>
                            </div>

                            <!-- Campo Fondo -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-image"></i> Fondo del Diploma
                                </label>
                                <div class="file-upload">
                                    <input type="file" name="fondo" id="fondo" accept="image/*" required>
                                    <label for="fondo" class="upload-label">
                                        <div class="upload-content">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <span>Seleccionar archivo</span>
                                        </div>
                                    </label>
                                    <img id="preview_fondo" src="" alt="Vista previa Fondo" class="d-none"
                                        style="max-height: 100px; margin-top: 10px;">
                                </div>
                            </div>


                            <!-- Fecha de Emisión -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-calendar-day"></i> Fecha de Emisión
                                </label>
                                <div class="input-with-icon">
                                    <i class="fas fa-calendar"></i>
                                    <input type="date" name="fecha_emision" required>
                                </div>
                            </div>

                            <!-- Orientación -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-arrows-alt-h"></i> Orientación
                                </label>
                                <div class="select-with-icon">
                                    <i class="fas fa-expand"></i>
                                    <select name="orientacion" required>
                                        <option value="horizontal">Horizontal</option>
                                        <option value="vertical">Vertical</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="tipo_certificado">Tipo de Certificado</label>
                                <select name="tipo_certificado" id="tipo_certificado" class="form-control" required>
                                    <option value="generico"
                                        {{ old('tipo_certificado', $plantilla->tipo_certificado ?? '') == 'generico' ? 'selected' : '' }}>
                                        Genérico</option>
                                    <option value="convenio"
                                        {{ old('tipo_certificado', $plantilla->tipo_certificado ?? '') == 'convenio' ? 'selected' : '' }}>
                                        Convenio</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-font"></i> Fuente del texto del diploma
                                </label>
                                <div class="select-with-icon">
                                    <i class="fas fa-font"></i>
                                    <select id="fuente_selector" class="form-control"
                                        onchange="actualizarVistaPreviaFuente()" required>
                                        @foreach (['VisbyCF-RegularOblique.otf', 'VisbyCF-Bold.otf', 'VisbyCF-BoldOblique.otf', 'VisbyCF-DemiBold.otf', 'VisbyCF-DemiBoldOblique.otf', 'VisbyCF-ExtraBold.otf', 'VisbyCF-ExtraBoldOblique.otf', 'VisbyCF-Heavy.otf', 'VisbyCF-HeavyOblique.otf', 'VisbyCF-Light.otf', 'VisbyCF-LightOblique.otf', 'VisbyCF-Medium.otf', 'VisbyCF-MediumOblique.otf', 'VisbyCF-Thin.otf', 'VisbyCF-ThinOblique.otf'] as $fuente)
                                            <option value="{{ $fuente }}"
                                                {{ ($plantilla->fuente ?? '') === $fuente ? 'selected' : '' }}>
                                                {{ str_replace(['VisbyCF-', '.otf'], '', $fuente) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Campo oculto que Laravel recibe -->
                                <input type="hidden" name="fuente" id="fuente"
                                    value="{{ old('fuente', $plantilla->fuente ?? 'VisbyCF-RegularOblique.otf') }}">

                                <!-- Vista previa -->
                                <div class="mt-2">
                                    <span style="display:block;">Vista previa:</span>
                                    <p id="previewFuente" style="font-size: 20px;">
                                        Ejemplo de texto con la fuente seleccionada
                                    </p>
                                </div>
                            </div>

                            <div class="mb-4" id="titulo_convenio_div"
                                style="{{ old('tipo_certificado', $plantilla->tipo_certificado ?? '') == 'convenio' ? '' : 'display: none;' }}">
                                <label for="titulo_convenio">Título Personalizado (solo para convenio)</label>
                                <input type="text" name="titulo_convenio" id="titulo_convenio" class="form-control"
                                    value="{{ old('titulo_convenio', $plantilla->titulo_convenio ?? '') }}">
                            </div>

                            <script>
                                document.getElementById('tipo_certificado').addEventListener('change', function() {
                                    const isConvenio = this.value === 'convenio';
                                    document.getElementById('titulo_convenio_div').style.display = isConvenio ? 'block' : 'none';
                                });
                            </script>

                        </div>

                        <!-- Botón de Guardar -->
                        <div class="form-footer">
                            <button type="submit" class="submit-btn">
                                <i class="fas fa-save"></i> Guardar Plantilla
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Acciones -->
            @if ($plantillaExistente)
                <div class="actions-card">
                    <div class="card-header">
                        <h3><i class="fas fa-tasks"></i> Acciones Disponibles</h3>
                    </div>
                    <div class="card-body">
                        <div class="action-buttons">
                            <a href="{{ route('capacitaciones.diplomas.preview', $capacitacion->id) }}" target="_blank"
                                class="action-btn preview">
                                <i class="fas fa-eye"></i> Vista Previa
                            </a>
                            <a href="{{ route('capacitaciones.diplomas', $capacitacion->id) }}"
                                class="action-btn generate">
                                <i class="fas fa-certificate"></i> Generar Diplomas
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- CDNs -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script>
        function vistaPrevia(inputId, previewId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);

            if (!input || !preview) return;

            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        preview.src = event.target.result;
                        preview.classList.remove('d-none');
                    };
                    reader.readAsDataURL(file);
                } else {
                    preview.src = '';
                    preview.classList.add('d-none');
                }
            });
        }

        vistaPrevia('firma_1', 'preview_firma_1');
        vistaPrevia('firma_2', 'preview_firma_2');
        vistaPrevia('fondo', 'preview_fondo');
    </script>
    <script>
        function actualizarVistaPreviaFuente() {
            const select = document.getElementById('fuente_selector');
            const preview = document.getElementById('previewFuente');
            const hiddenInput = document.getElementById('fuente');

            const fuente = select.value;
            const nombre = fuente.replace('.otf', '').replace('VisbyCF-', '');

            preview.textContent = 'Ejemplo de texto con fuente ' + nombre;
            preview.style.fontFamily = fuente.replace('.otf', '');

            hiddenInput.value = fuente;
        }


        // Ejecutar al cargar
        document.addEventListener('DOMContentLoaded', () => {
            actualizarVistaPreviaFuente();
        });
    </script>

    <style>
        /* Variables de color */
        :root {
            --primary-color: #4361ee;
            --primary-light: #6c7ef0;
            --primary-dark: #3a56d4;
            --success-color: #28a745;
            --error-color: #dc3545;
            --text-color: #333;
            --light-gray: #f8f9fa;
            --medium-gray: #e9ecef;
            --dark-gray: #6c757d;
            --white: #ffffff;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        /* Estilos base */
        .diploma-template-container {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-color);
            line-height: 1.6;
            max-width: 1000px;
            margin: 0 auto;
            padding-bottom: 2rem;
        }

        /* Encabezado con efecto gradiente */
        .template-header {
            position: relative;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: var(--white);
            padding: 2rem 2rem 4rem;
            margin-bottom: 2rem;
            text-align: center;
            border-radius: 0 0 20px 20px;
            overflow: hidden;
        }

        .header-content {
            position: relative;
            z-index: 2;
        }

        .template-header h2 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .template-header h2 i {
            margin-right: 0.5rem;
        }

        .template-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .header-wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100px;
        }

        .header-wave svg {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        /* Contenido principal */
        .template-content {
            padding: 0 1.5rem;
        }

        /* Mensajes de alerta */
        .alert-message {
            display: flex;
            align-items: center;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            box-shadow: var(--shadow);
            animation: slideIn 0.5s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .alert-message .icon {
            font-size: 1.5rem;
            margin-right: 1rem;
        }

        .alert-message .text {
            flex: 1;
        }

        .alert-message .close-btn {
            cursor: pointer;
            opacity: 0.7;
            transition: var(--transition);
        }

        .alert-message .close-btn:hover {
            opacity: 1;
        }

        .alert-message.success {
            background-color: rgba(40, 167, 69, 0.1);
            border-left: 4px solid var(--success-color);
            color: var(--success-color);
        }

        .alert-message.error {
            background-color: rgba(220, 53, 69, 0.1);
            border-left: 4px solid var(--error-color);
            color: var(--error-color);
        }

        /* Tarjetas */
        .form-card,
        .actions-card {
            background: var(--white);
            border-radius: 12px;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: var(--white);
            padding: 1.2rem 1.5rem;
        }

        .card-header h3 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 600;
        }

        .card-header h3 i {
            margin-right: 0.7rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Formulario */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--primary-dark);
        }

        .form-label i {
            margin-right: 0.5rem;
            width: 20px;
            text-align: center;
        }

        /* Campos de archivo personalizados */
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

        .upload-label {
            display: block;
            border: 2px dashed var(--medium-gray);
            border-radius: 8px;
            padding: 1.5rem;
            text-align: center;
            transition: var(--transition);
        }

        .upload-label:hover {
            border-color: var(--primary-light);
            background-color: rgba(67, 97, 238, 0.05);
        }

        .upload-content i {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .upload-content span {
            display: block;
            color: var(--dark-gray);
        }

        /* Campos con iconos */
        .input-with-icon,
        .select-with-icon {
            position: relative;
        }

        .input-with-icon i,
        .select-with-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--dark-gray);
        }

        .input-with-icon input,
        .select-with-icon select {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid var(--medium-gray);
            border-radius: 8px;
            transition: var(--transition);
        }

        .input-with-icon input:focus,
        .select-with-icon select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
            outline: none;
        }

        /* Botones */
        .submit-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-color);
            color: var(--white);
            border: none;
            padding: 0.8rem 1.8rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 4px 6px rgba(67, 97, 238, 0.2);
        }

        .submit-btn i {
            margin-right: 0.5rem;
        }

        .submit-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(67, 97, 238, 0.3);
        }

        .form-footer {
            text-align: center;
            margin-top: 1.5rem;
        }

        /* Acciones */
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            transition: var(--transition);
            box-shadow: var(--shadow);
        }

        .action-btn i {
            margin-right: 0.5rem;
        }

        .action-btn.preview {
            background: var(--white);
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
        }

        .action-btn.preview:hover {
            background: rgba(67, 97, 238, 0.1);
        }

        .action-btn.generate {
            background: var(--primary-color);
            color: var(--white);
        }

        .action-btn.generate:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .template-header h2 {
                font-size: 1.6rem;
            }

            .template-header p {
                font-size: 1rem;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }

            .action-btn {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .template-header {
                padding: 1.5rem 1rem 3rem;
            }

            .card-header h3 {
                font-size: 1.1rem;
            }

            .card-body {
                padding: 1rem;
            }
        }
    </style>

    <script>
        // Cierre de mensajes de alerta
        document.querySelectorAll('.close-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                this.parentElement.style.opacity = '0';
                setTimeout(() => {
                    this.parentElement.remove();
                }, 300);
            });
        });
    </script>
@endsection
