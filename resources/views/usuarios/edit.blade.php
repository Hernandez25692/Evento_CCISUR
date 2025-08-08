@extends('layouts.app')

@section('content')
    <div class="container py-5" style="min-height: 100vh; background: #f8fafc;">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="mb-4 text-center">
                    <h2 class="fw-bold text-primary">Editar Usuario</h2>
                    <p class="text-muted">Actualiza la información del usuario en el sistema.</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger shadow-sm">
                        <strong>Corrige los errores:</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-5">
                        <form action="{{ route('usuarios.update', $usuario) }}" method="POST" class="row g-4">
                            @csrf @method('PUT')
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nombre</label>
                                <input type="text" name="name" value="{{ old('name', $usuario->name) }}" class="form-control form-control-lg rounded-3"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Correo</label>
                                <input type="email" name="email" value="{{ old('email', $usuario->email) }}"
                                    class="form-control form-control-lg rounded-3" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nueva contraseña <span class="text-muted"></span></label>
                                <div class="input-group">
                                    <input type="password" name="password" class="form-control form-control-lg rounded-3" minlength="8" id="password">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Mínimo 8 caracteres</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Confirmar contraseña</label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" class="form-control form-control-lg rounded-3" minlength="8" id="password_confirmation">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <script>
                                function togglePassword(id) {
                                    const input = document.getElementById(id);
                                    if (input.type === "password") {
                                        input.type = "text";
                                    } else {
                                        input.type = "password";
                                    }
                                }
                            </script>
                            <div class="col-12 d-flex justify-content-end mt-4">
                                <a href="{{ route('usuarios.index') }}" class="btn btn-light btn-lg rounded-3 me-2 shadow-sm">Cancelar</a>
                                <button class="btn btn-primary btn-lg rounded-3 shadow-sm">Actualizar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
