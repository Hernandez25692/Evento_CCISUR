@extends('layouts.app')

@section('content')
    <div class="container py-5" style="min-height: 100vh; background: linear-gradient(135deg, #f8fafc 0%, #e0e7ef 100%);">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">
                <div class="text-center mb-4">
                    
                    <h2 class="fw-bold mt-3 mb-1" style="color: #2d3748;">Registrar Nuevo Usuario</h2>
                    <p class="text-muted">Completa el formulario para agregar un nuevo usuario al sistema.</p>
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
                    <div class="card-body p-4">
                        <form action="{{ route('usuarios.store') }}" method="POST" class="row g-4">
                            @csrf
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nombre</label>
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control form-control-lg rounded-3" required placeholder="Ej. Juan Pérez">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Correo</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control form-control-lg rounded-3" required placeholder="Ej. juan@email.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Contraseña</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control form-control-lg rounded-3" required minlength="8" placeholder="Mínimo 8 caracteres">
                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password', this)">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Confirmar contraseña</label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control form-control-lg rounded-3" required minlength="8" placeholder="Repite la contraseña">
                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password_confirmation', this)">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <script>
                                function togglePassword(fieldId, btn) {
                                    const input = document.getElementById(fieldId);
                                    const icon = btn.querySelector('i');
                                    if (input.type === "password") {
                                        input.type = "text";
                                        icon.classList.remove('fa-eye');
                                        icon.classList.add('fa-eye-slash');
                                    } else {
                                        input.type = "password";
                                        icon.classList.remove('fa-eye-slash');
                                        icon.classList.add('fa-eye');
                                    }
                                }
                            </script>
                            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
                            <div class="col-12 d-flex justify-content-end mt-3">
                                <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary btn-lg rounded-3 px-4">Cancelar</a>
                                <button class="btn btn-primary btn-lg rounded-3 ms-2 px-4">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <small class="text-muted">© {{ date('Y') }} CCISUR. Todos los derechos reservados.</small>
                </div>
            </div>
        </div>
    </div>
@endsection
