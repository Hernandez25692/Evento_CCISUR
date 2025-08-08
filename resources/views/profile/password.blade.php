@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h3 class="mb-3">Cambiar contraseña</h3>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Corrige los errores:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('password.update') }}" class="row g-3">
                    @csrf @method('PUT')
                    <div class="col-md-6">
                        <label class="form-label">Contraseña actual</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="col-md-6"></div>
                    <div class="col-md-6">
                        <label class="form-label">Nueva contraseña</label>
                        <input type="password" name="password" class="form-control" required minlength="8">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirmar nueva contraseña</label>
                        <input type="password" name="password_confirmation" class="form-control" required minlength="8">
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <a href="{{ url()->previous() }}" class="btn btn-light">Cancelar</a>
                        <button class="btn btn-primary ms-2">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
