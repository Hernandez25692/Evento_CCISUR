@extends('layouts.app')

@section('content')
<div class="container mt-5" style="max-width: 500px;">
    <h3 class="text-center mb-4">🔑 Restablecer Contraseña</h3>

    <form method="POST" action="{{ route('password.reset-with-code') }}">
        @csrf

        <input type="hidden" name="email" value="{{ $email }}">
        <input type="hidden" name="code" value="{{ $code }}">

        <div class="mb-3">
            <label for="password" class="form-label">Nueva contraseña</label>
            <input type="password" name="password" id="password" class="form-control" required>
            @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
            @error('password_confirmation') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100">🔒 Guardar nueva contraseña</button>
    </form>
</div>
@endsection
