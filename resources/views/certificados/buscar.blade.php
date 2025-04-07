@extends('layouts.app')

@section('content')
    <div class="certificate-search-container">
        <!-- Hero Section -->
        <div class="search-hero bg-gradient-primary">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-xl-6">
                        <div class="search-card card border-0 shadow-lg">
                            <div class="card-header bg-white border-0 py-4">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="certificate-icon animate__animated animate__bounceIn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                            fill="currentColor" viewBox="0 0 16 16">
                                            <path
                                                d="M4.5 0a.5.5 0 0 1 .5.5V1h6V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM2 2a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H2z" />
                                            <path
                                                d="M2.5 4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H3a.5.5 0 0 1-.5-.5V4zM4 8.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5z" />
                                        </svg>
                                    </div>
                                    <h1 class="search-title mb-0 ms-3 animate__animated animate__fadeIn">
                                        Consulta de Certificados
                                        <span class="d-block text-muted fs-5 fw-normal mt-1">Encuentra tus diplomas de
                                            capacitación</span>
                                    </h1>
                                </div>
                            </div>

                            <div class="card-body px-4 px-md-5 py-4">
                                <form method="POST" action="{{ route('certificados.resultado') }}" id="searchForm"
                                    class="needs-validation" novalidate>
                                    @csrf

                                    <div class="form-floating mb-4">
                                        <input type="text"
                                            class="form-control form-control-lg border-2 @error('identidad') is-invalid @enderror"
                                            id="identidad" name="identidad" placeholder=" " required autocomplete="off"
                                            autofocus>
                                        <label for="identidad" class="text-secondary">
                                            <i class="fas fa-id-card me-2"></i>Número de Identidad
                                        </label>
                                        @error('identidad')
                                            <div class="invalid-feedback d-block animate__animated animate__fadeIn">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-lg w-100 py-3 search-btn"
                                        id="submitBtn">
                                        <span class="search-btn-content">
                                            <span id="buttonText">Buscar Certificados</span>
                                            <span id="buttonSpinner" class="spinner-border spinner-border-sm ms-2 d-none"
                                                role="status"></span>
                                        </span>
                                        <span class="search-btn-icon">
                                            <i class="fas fa-search"></i>
                                        </span>
                                    </button>
                                </form>
                            </div>

                            <div class="card-footer bg-transparent border-0 pt-0 pb-4 text-center">
                                <a href="#" class="text-decoration-none small text-muted help-link"
                                    data-bs-toggle="modal" data-bs-target="#helpModal">
                                    <i class="fas fa-question-circle me-1"></i> ¿Necesitas ayuda?
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Info Section -->
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="info-card h-100">
                                <div class="info-icon bg-primary-light">
                                    <i class="fas fa-clock text-primary"></i>
                                </div>
                                <h3 class="info-title">Resultados inmediatos</h3>
                                <p class="info-text">Obtén acceso instantáneo a tus certificados con solo ingresar tu
                                    identidad.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-card h-100">
                                <div class="info-icon bg-success-light">
                                    <i class="fas fa-file-download text-success"></i>
                                </div>
                                <h3 class="info-title">Descarga fácil</h3>
                                <p class="info-text">Descarga tus diplomas en formato PDF con un solo clic cuando los
                                    necesites.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-card h-100">
                                <div class="info-icon bg-warning-light">
                                    <i class="fas fa-shield-alt text-warning"></i>
                                </div>
                                <h3 class="info-title">Seguridad garantizada</h3>
                                <p class="info-text">Tus datos están protegidos y solo tú puedes acceder a tus certificados.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Ayuda -->
    <div class="modal fade" id="helpModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-info-circle me-2"></i>Ayuda</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex mb-3">
                        <div class="me-3 text-primary">
                            <i class="fas fa-info-circle fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Cómo buscar tus certificados</h6>
                            <p class="small mb-0">Ingresa tu número de identidad completo para buscar tus certificados.</p>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="me-3 text-primary">
                            <i class="fas fa-headset fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Soporte técnico</h6>
                            <p class="small mb-0">Si tienes problemas con la búsqueda, contacta al departamento de
                                registros.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary">Contactar soporte</button>
                </div>
            </div>
        </div>
    </div>

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Validación del formulario
            const form = document.getElementById('searchForm');
            const identidadInput = document.getElementById('identidad');

            form.addEventListener('submit', function(e) {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                } else {
                    const submitBtn = document.getElementById('submitBtn');
                    const buttonText = document.getElementById('buttonText');
                    const buttonSpinner = document.getElementById('buttonSpinner');

                    submitBtn.disabled = true;
                    buttonText.textContent = 'Buscando...';
                    buttonSpinner.classList.remove('d-none');
                }
                form.classList.add('was-validated');
            }, false);

            // Efecto hover para el botón
            const searchBtn = document.querySelector('.search-btn');
            if (searchBtn) {
                searchBtn.addEventListener('mouseenter', function() {
                    this.querySelector('.search-btn-icon').style.transform = 'translateX(5px)';
                    this.querySelector('.search-btn-content').style.transform = 'translateX(-5px)';
                });
                searchBtn.addEventListener('mouseleave', function() {
                    this.querySelector('.search-btn-icon').style.transform = 'translateX(0)';
                    this.querySelector('.search-btn-content').style.transform = 'translateX(0)';
                });
            }

            // Efecto hover para el enlace de ayuda
            const helpLink = document.querySelector('.help-link');
            if (helpLink) {
                helpLink.addEventListener('mouseenter', function() {
                    this.querySelector('i').style.transform = 'rotate(15deg)';
                });
                helpLink.addEventListener('mouseleave', function() {
                    this.querySelector('i').style.transform = 'rotate(0)';
                });
            }
        });
    </script>
@endsection

@section('styles')
    <style>
        :root {
            --primary-color: #4e73df;
            --primary-dark: #224abe;
            --primary-light: #e6f0ff;
            --success-color: #1cc88a;
            --success-light: #e6f8f1;
            --warning-color: #f6c23e;
            --warning-light: #fef8e8;
            --text-color: #5a5c69;
            --text-light: #858796;
            --shadow-sm: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            --shadow-md: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            --shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.175);
            --transition: all 0.3s ease;
        }

        .certificate-search-container {
            min-height: 100vh;
            background-color: #f8f9fc;
        }

        .search-hero {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            padding: 5rem 0;
        }

        .search-card {
            border-radius: 1rem;
            border: none;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .search-card:hover {
            transform: translateY(-0.5rem);
            box-shadow: 0 1rem 2.5rem rgba(0, 0, 0, 0.15);
        }

        .certificate-icon {
            color: var(--primary-color);
            background-color: var(--primary-light);
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-sm);
        }

        .search-title {
            font-weight: 700;
            color: var(--text-color);
            font-size: 1.75rem;
        }

        .form-floating label {
            transition: var(--transition);
        }

        .form-control-lg {
            height: calc(3.5rem + 2px);
            padding: 1rem 1.5rem;
            font-size: 1.1rem;
            border-color: #d1d3e2;
            transition: var(--transition);
        }

        .form-control-lg:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
        }

        .search-btn {
            position: relative;
            overflow: hidden;
            border: none;
            background: linear-gradient(to right, var(--primary-color) 0%, var(--primary-dark) 100%);
            transition: var(--transition);
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        .search-btn:hover {
            background: linear-gradient(to right, var(--primary-dark) 0%, var(--primary-color) 100%);
            transform: translateY(-2px);
        }

        .search-btn-content {
            display: inline-block;
            transition: var(--transition);
        }

        .search-btn-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            transition: var(--transition);
        }

        .help-link {
            transition: var(--transition);
        }

        .help-link:hover {
            color: var(--primary-color) !important;
        }

        .help-link i {
            transition: transform 0.3s ease;
        }

        /* Info Cards */
        .info-card {
            background: white;
            border-radius: 0.75rem;
            padding: 2rem 1.5rem;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            border: 1px solid rgba(0, 0, 0, 0.03);
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        .info-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .info-title {
            font-size: 1.25rem;
            color: var(--text-color);
            margin-bottom: 1rem;
        }

        .info-text {
            color: var(--text-light);
            margin-bottom: 0;
        }

        .bg-primary-light {
            background-color: var(--primary-light);
        }

        .bg-success-light {
            background-color: var(--success-light);
        }

        .bg-warning-light {
            background-color: var(--warning-light);
        }

        /* Animations */
        .animate__animated {
            animation-duration: 0.5s;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .search-hero {
                padding: 4rem 0;
            }

            .search-title {
                font-size: 1.5rem;
            }

            .certificate-icon {
                width: 60px;
                height: 60px;
            }
        }

        @media (max-width: 768px) {
            .search-hero {
                padding: 3rem 0;
            }

            .search-card {
                margin-top: 1rem;
            }

            .info-card {
                margin-bottom: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .search-title {
                font-size: 1.3rem;
            }

            .card-body {
                padding: 1.5rem;
            }

            .form-control-lg {
                font-size: 1rem;
            }
        }
    </style>
@endsection
@endsection
