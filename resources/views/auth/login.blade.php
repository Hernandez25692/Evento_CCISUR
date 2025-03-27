@extends('layouts.app')

@section('content')
<section class="auth-container">
    <div class="auth-background">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="auth-content">
                    <!-- Columna de presentación -->
                    <div class="auth-presentation">
                        <div class="presentation-content">
                            <h1 class="display-4 fw-bold">
                                Bienvenido a <span class="text-highlight">Evento_CCISUR</span>
                            </h1>
                            <p class="lead">
                                Plataforma de gestión de Formaciones para la Cámara de Comercio e Industrias del Sur.
                            </p>
                            <div class="features">
                                <div class="feature-item">
                                    <i class="fas fa-calendar-check"></i>
                                    <span>Gestión de eventos</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-users"></i>
                                    <span>Control de participantes</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-certificate"></i>
                                    <span>Generación de diplomas</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Columna de formulario -->
                    <div class="auth-form-container">
                        <div class="auth-form-card">
                            <div class="card-header">
                                <div class="logo-container">
                                    <img src="{{ asset('storage/logo/Logo-CCISUR.png') }}" alt="Logo CCISUR" class="logo">
                                </div>
                                <h2>Iniciar Sesión</h2>
                            </div>

                            <div class="card-body">
                                @if(session('status'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                                @endif

                                @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                                @endif

                                <form method="POST" action="{{ route('login') }}" class="auth-form">
                                    @csrf

                                    <div class="form-group">
                                        <label for="email" class="form-label">
                                            <i class="fas fa-envelope me-2"></i>Correo electrónico
                                        </label>
                                        <input type="email" id="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email') }}" required autofocus>
                                        @error('email')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="password" class="form-label">
                                            <i class="fas fa-lock me-2"></i>Contraseña
                                        </label>
                                        <div class="password-input">
                                            <input type="password" id="password" name="password"
                                                class="form-control @error('password') is-invalid @enderror" required>
                                            <button type="button" class="toggle-password">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-options">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                                {{ old('remember') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="remember">
                                                Recuérdame
                                            </label>
                                        </div>
                                        @if (Route::has('password.request-code'))
                                        <a href="{{ route('password.request-code') }}" class="forgot-password">
                                            <i class="fas fa-key me-1"></i>¿Olvidaste tu contraseña?
                                        </a>
                                        @endif
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100 auth-submit">
                                        <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CDNs -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
    :root {
        --primary-color: #4361ee;
        --primary-light: #6c7ef0;
        --primary-dark: #3a56d4;
        --secondary-color: #6c757d;
        --success-color: #28a745;
        --danger-color: #dc3545;
        --light-color: #f8f9fa;
        --dark-color: #212529;
        --text-color: #333;
        --white: #ffffff;
        --transition: all 0.3s ease;
        --shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        --glass-bg: rgba(255, 255, 255, 0.9);
    }

    /* Estructura principal */
    .auth-container {
        position: relative;
        min-height: 100vh;
        display: flex;
        align-items: center;
        padding: 2rem 0;
        overflow: hidden;
    }

    .auth-background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: hsl(218, 41%, 15%);
        background-image: radial-gradient(650px circle at 0% 0%,
                hsl(218, 41%, 35%) 15%,
                hsl(218, 41%, 30%) 35%,
                hsl(218, 41%, 20%) 75%,
                hsl(218, 41%, 19%) 80%,
                transparent 100%),
            radial-gradient(1250px circle at 100% 100%,
                hsl(218, 41%, 45%) 15%,
                hsl(218, 41%, 30%) 35%,
                hsl(218, 41%, 20%) 75%,
                hsl(218, 41%, 19%) 80%,
                transparent 100%);
        z-index: -1;
    }

    .shape {
        position: absolute;
        border-radius: 50%;
        background: radial-gradient(#44006b, #ad1fff);
        filter: blur(30px);
        opacity: 0.15;
    }

    .shape-1 {
        height: 220px;
        width: 220px;
        top: -60px;
        left: -130px;
    }

    .shape-2 {
        width: 300px;
        height: 300px;
        bottom: -60px;
        right: -110px;
        border-radius: 38% 62% 63% 37% / 70% 33% 67% 30%;
    }

    /* Contenido de autenticación */
    .auth-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        background: var(--white);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: var(--shadow);
    }

    /* Presentación */
    .auth-presentation {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: var(--white);
        padding: 3rem;
        display: flex;
        align-items: center;
    }

    .presentation-content {
        max-width: 100%;
    }

    .presentation-content h1 {
        font-size: 2.2rem;
        line-height: 1.3;
        margin-bottom: 1.5rem;
    }

    .text-highlight {
        color: #6ecff6;
    }

    .presentation-content .lead {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 2rem;
    }

    .features {
        display: grid;
        gap: 1rem;
    }

    .feature-item {
        display: flex;
        align-items: center;
        font-size: 1rem;
    }

    .feature-item i {
        margin-right: 0.75rem;
        font-size: 1.2rem;
        color: #6ecff6;
    }

    /* Formulario */
    .auth-form-container {
        padding: 3rem;
        background: var(--white);
    }

    .auth-form-card {
        max-width: 400px;
        margin: 0 auto;
    }

    .card-header {
        text-align: center;
        margin-bottom: 2rem;
        padding: 0;
        background: transparent;
        border: none;
    }

    .logo-container {
        margin-bottom: 1.5rem;
    }

    .logo {
        height: 80px;
        width: auto;
    }

    .card-header h2 {
        font-size: 1.8rem;
        color: var(--primary-color);
        margin-bottom: 0;
    }

    /* Formulario */
    .auth-form {
        display: grid;
        gap: 1.5rem;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: var(--dark-color);
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #ced4da;
        border-radius: 8px;
        transition: var(--transition);
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
    }

    .password-input {
        position: relative;
    }

    .toggle-password {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--secondary-color);
        cursor: pointer;
    }

    /* Opciones del formulario */
    .form-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 1rem 0;
    }

    .form-check {
        display: flex;
        align-items: center;
    }

    .form-check-input {
        margin-right: 0.5rem;
    }

    .forgot-password {
        color: var(--primary-color);
        text-decoration: none;
        font-size: 0.9rem;
        transition: var(--transition);
    }

    .forgot-password:hover {
        color: var(--primary-dark);
        text-decoration: underline;
    }

    /* Botón de submit */
    .auth-submit {
        background: var(--primary-color);
        border: none;
        padding: 0.75rem;
        font-size: 1rem;
        font-weight: 500;
        border-radius: 8px;
        transition: var(--transition);
    }

    .auth-submit:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
    }

    /* Alertas */
    .alert {
        border-radius: 8px;
        padding: 0.75rem 1rem;
    }

    .alert-success {
        background-color: rgba(40, 167, 69, 0.1);
        border-left: 4px solid var(--success-color);
        color: var(--success-color);
    }

    .alert-danger {
        background-color: rgba(220, 53, 69, 0.1);
        border-left: 4px solid var(--danger-color);
        color: var(--danger-color);
    }

    /* Responsive */
    @media (max-width: 992px) {
        .auth-content {
            grid-template-columns: 1fr;
        }
        
        .auth-presentation {
            padding: 2rem;
        }
        
        .auth-form-container {
            padding: 2rem;
        }
    }

    @media (max-width: 576px) {
        .auth-container {
            padding: 1rem;
        }
        
        .presentation-content h1 {
            font-size: 1.8rem;
        }
        
        .form-options {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
        
        .card-header h2 {
            font-size: 1.5rem;
        }
    }

    /* Animaciones */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .auth-form-card {
        animation: fadeIn 0.5s ease-out;
    }
</style>

<script>
    // Mostrar/ocultar contraseña
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const passwordInput = this.parentElement.querySelector('input');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });
    });
</script>
@endsection