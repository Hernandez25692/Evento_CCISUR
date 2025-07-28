<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Certificados | CCISUR</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Consulta y descarga tus certificados emitidos por CCISUR">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a0ca3;
            --primary-light: #e6f0ff;
            --secondary: #4cc9f0;
            --text: #2b2d42;
            --text-light: #858796;
            --white: #ffffff;
            --gray: #f8f9fa;
            --success: #4bb543;
            --error: #ef233c;
            --border-radius: 12px;
            --box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text);
            background-color: var(--gray);
            line-height: 1.6;
        }

        .app-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        .app-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: var(--white);
            padding: 1.5rem 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo-icon {
            width: 50px;
            height: 50px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .logo-text {
            font-weight: 700;
            font-size: 1.5rem;
        }

        .logo-subtext {
            font-size: 0.9rem;
            opacity: 0.8;
            font-weight: 300;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 3rem 0;
            display: flex;
            align-items: center;
        }

        .certificate-card {
            background-color: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            overflow: hidden;
            transition: var(--transition);
        }

        .certificate-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: var(--white);
            padding: 1.5rem;
            text-align: center;
        }

        .certificate-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--white);
        }

        .certificate-title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .certificate-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            font-weight: 300;
        }

        .certificate-body {
            padding: 2rem;
        }

        .form-label {
            font-weight: 500;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
        }

        .input-group-text {
            background-color: var(--primary-light);
            color: var(--primary);
            border: none;
        }

        .form-control {
            border: 1px solid #e0e0e0;
            border-radius: var(--border-radius);
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: var(--border-radius);
            padding: 0.75rem;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: var(--transition);
            width: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(67, 97, 238, 0.2);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-loading .spinner {
            margin-left: 0.5rem;
        }

        .info-text {
            font-size: 0.9rem;
            color: var(--text-light);
            text-align: center;
            margin-top: 1.5rem;
        }

        .info-text i {
            color: var(--primary);
            margin-right: 0.5rem;
        }

        .help-link {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .help-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        /* Footer */
        .app-footer {
            background-color: var(--white);
            padding: 1.5rem 0;
            text-align: center;
            font-size: 0.9rem;
            color: var(--text-light);
            box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.05);
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-bottom: 1rem;
        }

        .footer-link {
            color: var(--text-light);
            text-decoration: none;
            transition: var(--transition);
        }

        .footer-link:hover {
            color: var(--primary);
        }

        /* Modal */
        .modal-content {
            border-radius: var(--border-radius);
            border: none;
            overflow: hidden;
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: var(--white);
            border-bottom: none;
            padding: 1.5rem;
        }

        .modal-title {
            font-weight: 600;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .help-item {
            margin-bottom: 1.5rem;
        }

        .help-item:last-child {
            margin-bottom: 0;
        }

        .help-item-title {
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
        }

        .contact-link {
            color: var(--primary);
            text-decoration: none;
        }

        .contact-link:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .certificate-title {
                font-size: 1.5rem;
            }

            .certificate-body {
                padding: 1.5rem;
            }

            .logo-text {
                font-size: 1.3rem;
            }
        }

        @media (max-width: 576px) {
            .main-content {
                padding: 1.5rem 0;
            }

            .certificate-header {
                padding: 1rem;
            }

            .certificate-title {
                font-size: 1.3rem;
            }

            .certificate-body {
                padding: 1.25rem;
            }

            .footer-links {
                flex-direction: column;
                gap: 0.5rem;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }

        /* Validation */
        .is-invalid {
            border-color: var(--error) !important;
        }

        .invalid-feedback {
            color: var(--error);
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }
    </style>
</head>

<body class="app-container">
    <!-- Header -->
    <header class="app-header">
        <div class="container">
            <div class="logo-container">
                <div class="logo-icon" style="background: linear-gradient(135deg, #4cc9f0 0%, #4361ee 100%); box-shadow: 0 2px 8px rgba(67,97,238,0.15);">
                    <i class="fas fa-certificate"></i>
                </div>
                <div>
                
                    <div class="logo-text" style="letter-spacing: 1px; font-size: 1.7rem;">
                        <span style="color: #4cc9f0;">FORMACIONES</span> <span style="color: #fff;">CCISUR</span>
                    </div>
                    <div class="logo-subtext" style="font-size: 1rem; color: #e6f0ff;">
                        Plataforma de gestión de Formaciones para la <span style="font-weight:500;">Cámara de Comercio e Industrias del Sur</span>.
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container animate-fade-in">
            <div class="certificate-card">
                <div class="certificate-header">
                    <div class="certificate-icon">
                        <img src="{{ asset('storage/logo_diploma/ccisur para fondo azul.png') }}" alt="Logo CCISUR" style="max-width: 120px; max-height: 120px;">
                    </div>
                    <h1 class="certificate-title">Gestión de Certificados</h1>
                    <p class="certificate-subtitle">Consulta y descarga tus diplomas de Formaciones</p>
                </div>

                <div class="certificate-body">
                    <form method="POST" action="{{ route('certificados.resultado') }}" id="certSearchForm" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label for="identidad" class="form-label">Número de Identidad</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                <input type="text" class="form-control @error('identidad') is-invalid @enderror"
                                    id="identidad" name="identidad" placeholder="Ejemplo: 0801199912345" required
                                    autocomplete="off" autofocus maxlength="13" pattern="\d{13}" inputmode="numeric">
                            </div>
                            @error('identidad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary" id="certBtn">
                            <span>Buscar Certificados</span>
                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>

                        <p class="info-text">
                            <i class="fas fa-info-circle"></i>
                            Ingresa tu número de identidad completo (13 dígitos, sin guiones).
                        </p>

                        <a href="#" class="help-link" data-bs-toggle="modal" data-bs-target="#helpModal">
                            <i class="fas fa-question-circle"></i> ¿Necesitas ayuda?
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="app-footer">
        <div class="container">
            <div class="footer-links">
                <a href="#" class="footer-link">Términos de servicio</a>
                <a href="#" class="footer-link">Política de privacidad</a>
                <a href="#" class="footer-link">Contacto</a>
            </div>
            <div>
                &copy; {{ date('Y') }} CCISUR. Todos los derechos reservados.
            </div>
        </div>
    </footer>

    <!-- Help Modal -->
    <div class="modal fade" id="helpModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-question-circle me-2"></i> Centro de Ayuda</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="help-item">
                        <h6 class="help-item-title"><i class="fas fa-search me-2"></i>¿Cómo buscar tu certificado?</h6>
                        <p>Ingresa tu número de identidad completo (13 dígitos, sin guiones ni espacios) en el campo
                            correspondiente y haz clic en el botón <strong>"Buscar Certificados"</strong>.</p>
                    </div>

                    <div class="help-item">
                        <h6 class="help-item-title"><i class="fas fa-exclamation-circle me-2"></i>¿No encuentras tu
                            certificado?</h6>
                        <p>Verifica que hayas ingresado correctamente tu número de identidad. Si el problema persiste,
                            puede que tu certificado aún no esté disponible en el sistema o haya un error en los datos.
                        </p>
                    </div>

                    <div class="help-item">
                        <h6 class="help-item-title"><i class="fas fa-headset me-2"></i>Soporte técnico</h6>
                        <p>Para asistencia personalizada, contacta a nuestro equipo de soporte:</p>
                        <p>
                            <i class="fas fa-envelope me-2"></i>
                            <a href="mailto:soporte@ccisur.edu.hn" class="contact-link">soporte@ccisur.edu.hn</a>
                        </p>
                        <p>
                            <i class="fas fa-phone me-2"></i>
                            <a href="tel:+50412345678" class="contact-link">+504 1234-5678</a>
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('certSearchForm');
            const btn = document.getElementById('certBtn');
            const spinner = btn.querySelector('.spinner-border');

            // Form validation and submission
            form.addEventListener('submit', function(e) {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                } else {
                    btn.disabled = true;
                    btn.classList.add('btn-loading');
                    spinner.classList.remove('d-none');
                }

                form.classList.add('was-validated');
            }, false);

            // Input formatting for identity number
            const identidadInput = document.getElementById('identidad');
            identidadInput.addEventListener('input', function(e) {
                // Remove any non-digit characters
                this.value = this.value.replace(/\D/g, '');

                // Validate length
                if (this.value.length > 13) {
                    this.value = this.value.slice(0, 13);
                }
            });

            // Add animation class to elements
            const animateElements = document.querySelectorAll('.animate-fade-in');
            animateElements.forEach((el, index) => {
                el.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
</body>

</html>
