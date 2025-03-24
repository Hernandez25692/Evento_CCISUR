@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow-lg p-4 rounded" style="width: 100%; max-width: 400px;">
        <div class="text-center mb-4">
            <img src="{{ asset('storage/logo/Logo-CCISUR.png') }}" alt="Logo CCISUR" style="width: 80px;">
            <h4 class="mt-3 text-primary fw-bold">Inicio de Sesión</h4>
        </div>

        <!-- Mostrar mensajes de éxito o error -->
        @if(session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                    name="email" value="{{ old('email') }}" required autofocus placeholder="ejemplo@correo.com">

                @error('email')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                    name="password" required placeholder="********">

                @error('password')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3 d-flex justify-content-between align-items-center">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                        {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        Recuérdame
                    </label>
                </div>
                @if (Route::has('password.request'))
                    <a class="text-decoration-none small" href="{{ route('password.request') }}">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
            </div>

            <button type="submit" class="btn btn-custom w-100">
                🔐 Iniciar Sesión
            </button>
        </form>
    </div>
</div>

<!-- Estilos personalizados -->
<style>
    .btn-custom {
        background-color: #1abc9c;
        color: white;
        font-weight: bold;
        transition: 0.3s ease-in-out;
    }

    .btn-custom:hover {
        background-color: #159a85;
        transform: scale(1.03);
    }
</style>
@endsection
