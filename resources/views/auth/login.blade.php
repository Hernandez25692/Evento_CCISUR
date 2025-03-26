@extends('layouts.app')

@section('content')
<section class="background-radial-gradient overflow-hidden py-5">
    <style>
        .background-radial-gradient {
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
        }

        #radius-shape-1 {
            height: 220px;
            width: 220px;
            top: -60px;
            left: -130px;
            background: radial-gradient(#44006b, #ad1fff);
        }

        #radius-shape-2 {
            border-radius: 38% 62% 63% 37% / 70% 33% 67% 30%;
            bottom: -60px;
            right: -110px;
            width: 300px;
            height: 300px;
            background: radial-gradient(#44006b, #ad1fff);
        }

        .bg-glass {
            background-color: hsla(0, 0%, 100%, 0.9) !important;
            backdrop-filter: saturate(200%) blur(25px);
        }

        .form-label {
            font-weight: bold;
        }
    </style>

    <div class="container px-4 px-md-5 text-center text-lg-start my-5">
        <div class="row gx-lg-5 align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0 text-white">
                <h1 class="display-4 fw-bold ls-tight">
                    Bienvenido a <br />
                    <span class="text-info">Evento_CCISUR</span>
                </h1>
                <p class="mb-4">
                    Plataforma de gestión de Formaciones para la Cámara de Comercio e Industrias del Sur.
                </p>
            </div>

            <div class="col-lg-6 position-relative">
                <div id="radius-shape-1" class="position-absolute rounded-circle shadow-5-strong"></div>
                <div id="radius-shape-2" class="position-absolute shadow-5-strong"></div>

                <div class="card bg-glass">
                    <div class="card-body px-4 py-5">
                        <div class="text-center mb-4">
                            <img src="{{ asset('storage/logo/Logo-CCISUR.png') }}" width="80" alt="Logo">
                        </div>

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

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-outline mb-4">
                                <label for="email" class="form-label">Correo electrónico</label>
                                <input type="email" id="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}" required autofocus>
                                @error('email')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-outline mb-4">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" id="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-check d-flex justify-content-between mb-3">
                                <div>
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                        {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember"> Recuérdame </label>
                                </div>
                                    @if (Route::has('password.request-code'))
            <a class="text-decoration-none" href="{{ route('password.request-code') }}">
                🔐 ¿Olvidaste tu contraseña?
            </a>
        @endif
                            </div>

                            <button type="submit" class="btn btn-info w-100 fw-bold">
                                🔐 Iniciar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
