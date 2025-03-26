@extends('layouts.app')

@section('content')
<div class="container mt-5" style="max-width: 500px;">
    <h3 class="text-center mb-4">🔐 Solicitar Código de Recuperación</h3>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.send-code') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" name="email" id="email" class="form-control" required autofocus>
            @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100">📤 Enviar código</button>
    </form>
</div>
@endsection
