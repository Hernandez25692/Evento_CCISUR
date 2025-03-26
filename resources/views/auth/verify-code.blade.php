@extends('layouts.app')

@section('content')
<div class="container mt-5" style="max-width: 500px;">
    <h3 class="text-center mb-4">📩 Verificar Código</h3>

    <form method="POST" action="{{ route('password.verify-code') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ session('email') ?? old('email') }}" required>
            @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="code" class="form-label">Código recibido</label>
            <input type="text" name="code" id="code" class="form-control" required>
            @error('code') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-success w-100">✅ Verificar Código</button>
    </form>
</div>
@endsection
