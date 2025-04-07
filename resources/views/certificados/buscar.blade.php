@extends('layouts.app')

@section('content')
<div class="certificate-search">
    <!-- Hero Section with Gradient Background -->
    <section class="search-hero">
        <div class="hero-container">
            <div class="search-card">
                <div class="card-header">
                    <div class="header-content">
                        <div class="certificate-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M19 5h-2V3H7v2H5c-1.1 0-2 .9-2 2v1c0 2.55 1.92 4.63 4.39 4.94.63 1.5 1.98 2.63 3.61 2.96V19H7v2h10v-2h-4v-3.1c1.63-.33 2.98-1.46 3.61-2.96C19.08 12.63 21 10.55 21 8V7c0-1.1-.9-2-2-2zM5 8V7h2v3.82C5.84 10.4 5 9.3 5 8zm14 0c0 1.3-.84 2.4-2 2.82V7h2v1z"/>
                            </svg>
                        </div>
                        <h1 class="search-title">
                            Consulta de Certificados
                            <span class="subtitle">Encuentra tus diplomas de capacitación</span>
                        </h1>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('certificados.resultado') }}" id="searchForm" class="needs-validation" novalidate>
                        @csrf

                        <div class="form-group">
                            <div class="input-container">
                                <i class="fas fa-id-card input-icon"></i>
                                <input type="text" class="form-control @error('identidad') is-invalid @enderror" 
                                       id="identidad" name="identidad" placeholder="Número de Identidad sin guiones" 
                                       required autocomplete="off" autofocus>
                                <div class="input-border"></div>
                            </div>
                            @error('identidad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="search-button" id="submitBtn">
                            <span class="button-text">Buscar Certificados</span>
                            <span class="button-icon">
                                <i class="fas fa-search"></i>
                            </span>
                            <span class="button-spinner spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>
                    </form>
                </div>

                <div class="card-footer">
                    <a href="#" class="help-link" data-bs-toggle="modal" data-bs-target="#helpModal">
                        <i class="fas fa-question-circle"></i> ¿Necesitas ayuda?
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3 class="feature-title">Resultados inmediatos</h3>
                    <p class="feature-text">Obtén acceso instantáneo a tus certificados con solo ingresar tu identidad.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-cloud-download-alt"></i>
                    </div>
                    <h3 class="feature-title">Descarga fácil</h3>
                    <p class="feature-text">Descarga tus diplomas en formato PDF con un solo clic cuando los necesites.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h3 class="feature-title">Seguridad garantizada</h3>
                    <p class="feature-text">Tus datos están protegidos y solo tú puedes acceder a tus certificados.</p>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Help Modal -->
<div class="modal fade" id="helpModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-info-circle"></i> Ayuda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="help-item">
                    <div class="help-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="help-content">
                        <h6>Cómo buscar tus certificados</h6>
                        <p>Ingresa tu número de identidad completo para buscar tus certificados.</p>
                    </div>
                </div>
                <div class="help-item">
                    <div class="help-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <div class="help-content">
                        <h6>Soporte técnico</h6>
                        <p>Si tienes problemas con la búsqueda, contacta al departamento de registros.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary">Contactar soporte</button>
            </div>
        </div>
    </div>
</div>

<!-- CDNs -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

<style>
    :root {
        --primary: #4361ee;
        --primary-dark: #3a0ca3;
        --primary-light: #e6f0ff;
        --secondary: #4cc9f0;
        --success: #1cc88a;
        --warning: #f6c23e;
        --danger: #ef233c;
        --light: #f8f9fc;
        --dark: #212529;
        --text: #2b2d42;
        --text-light: #858796;
        --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.15);
        --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        --border-radius: 12px;
    }

    /* Base Styles */
    .certificate-search {
        min-height: 100vh;
        background-color: var(--light);
        font-family: 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
        color: var(--text);
    }

    /* Hero Section */
    .search-hero {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        padding: 5rem 1rem;
        position: relative;
        overflow: hidden;
    }

    .search-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
        opacity: 0.2;
    }

    .hero-container {
        max-width: 600px;
        margin: 0 auto;
        position: relative;
    }

    .search-card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        transition: var(--transition);
    }

    .search-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }

    .card-header {
        padding: 2rem;
        background: white;
        text-align: center;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .header-content {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .certificate-icon {
        width: 80px;
        height: 80px;
        background: var(--primary-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
    }

    .certificate-icon svg {
        width: 36px;
        height: 36px;
        color: var(--primary);
    }

    .search-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 0.5rem;
    }

    .subtitle {
        display: block;
        font-size: 1rem;
        font-weight: 400;
        color: var(--text-light);
        margin-top: 0.5rem;
    }

    .card-body {
        padding: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .input-container {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-light);
        z-index: 2;
    }

    .form-control {
        width: 100%;
        padding: 1rem 1rem 1rem 3rem;
        font-size: 1rem;
        border: 1px solid #e0e0e0;
        border-radius: var(--border-radius);
        transition: var(--transition);
        background-color: white;
        position: relative;
        z-index: 1;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
    }

    .input-border {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: var(--primary);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.3s ease;
        z-index: 3;
    }

    .form-control:focus ~ .input-border {
        transform: scaleX(1);
    }

    .invalid-feedback {
        color: var(--danger);
        font-size: 0.85rem;
        margin-top: 0.5rem;
        display: block;
    }

    .search-button {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(to right, var(--primary), var(--primary-dark));
        color: white;
        border: none;
        border-radius: var(--border-radius);
        font-size: 1rem;
        font-weight: 500;
        cursor: pointer;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .search-button:hover {
        background: linear-gradient(to right, var(--primary-dark), var(--primary));
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }

    .button-text {
        position: relative;
        z-index: 2;
        transition: var(--transition);
    }

    .button-icon {
        position: absolute;
        right: 1.5rem;
        top: 50%;
        transform: translateY(-50%);
        z-index: 2;
        transition: var(--transition);
    }

    .button-spinner {
        position: absolute;
        right: 1.5rem;
        top: 50%;
        transform: translateY(-50%);
        z-index: 2;
    }

    .search-button:hover .button-icon {
        transform: translateY(-50%) translateX(5px);
    }

    .search-button:hover .button-text {
        transform: translateX(-5px);
    }

    .card-footer {
        padding: 1rem 2rem 2rem;
        text-align: center;
        background: white;
    }

    .help-link {
        color: var(--text-light);
        text-decoration: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
    }

    .help-link:hover {
        color: var(--primary);
    }

    .help-link i {
        margin-right: 0.5rem;
        transition: var(--transition);
    }

    .help-link:hover i {
        transform: rotate(15deg);
    }

    /* Features Section */
    .features-section {
        padding: 4rem 1rem;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }

    .feature-card {
        background: white;
        border-radius: var(--border-radius);
        padding: 2rem;
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
        text-align: center;
    }

    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }

    .feature-icon {
        width: 70px;
        height: 70px;
        background: var(--primary-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        color: var(--primary);
        font-size: 1.5rem;
    }

    .feature-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: var(--text);
    }

    .feature-text {
        color: var(--text-light);
        margin-bottom: 0;
        font-size: 0.95rem;
    }

    /* Help Modal */
    .modal-content {
        border: none;
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }

    .modal-header {
        background: var(--primary);
        color: white;
        border: none;
        padding: 1.5rem;
    }

    .modal-title {
        display: flex;
        align-items: center;
    }

    .modal-title i {
        margin-right: 0.75rem;
    }

    .btn-close {
        filter: invert(1);
    }

    .modal-body {
        padding: 1.5rem;
    }

    .help-item {
        display: flex;
        margin-bottom: 1.5rem;
    }

    .help-item:last-child {
        margin-bottom: 0;
    }

    .help-icon {
        width: 40px;
        height: 40px;
        background: var(--primary-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .help-content h6 {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
        color: var(--text);
    }

    .help-content p {
        font-size: 0.9rem;
        color: var(--text-light);
        margin-bottom: 0;
    }

    .modal-footer {
        border: none;
        padding: 1rem 1.5rem;
    }

    /* Responsive Design */
    @media (max-width: 992px) {
        .search-hero {
            padding: 4rem 1rem;
        }
    }

    @media (max-width: 768px) {
        .search-title {
            font-size: 1.5rem;
        }

        .certificate-icon {
            width: 70px;
            height: 70px;
        }

        .features-grid {
            grid-template-columns: 1fr;
            max-width: 500px;
            margin: 0 auto;
        }
    }

    @media (max-width: 576px) {
        .search-hero {
            padding: 3rem 1rem;
        }

        .search-title {
            font-size: 1.3rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .card-header {
            padding: 1.5rem;
        }

        .feature-card {
            padding: 1.5rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form validation and submission
        const form = document.getElementById('searchForm');
        const submitBtn = document.getElementById('submitBtn');
        const buttonText = submitBtn.querySelector('.button-text');
        const buttonIcon = submitBtn.querySelector('.button-icon');
        const buttonSpinner = submitBtn.querySelector('.button-spinner');

        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            } else {
                submitBtn.disabled = true;
                buttonText.textContent = 'Buscando...';
                buttonIcon.classList.add('d-none');
                buttonSpinner.classList.remove('d-none');
            }
            form.classList.add('was-validated');
        }, false);

        // Input focus effect
        const input = document.getElementById('identidad');
        const inputBorder = document.querySelector('.input-border');

        input.addEventListener('focus', function() {
            inputBorder.style.transform = 'scaleX(1)';
        });

        input.addEventListener('blur', function() {
            if (!input.value) {
                inputBorder.style.transform = 'scaleX(0)';
            }
        });

        // Help link hover effect
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