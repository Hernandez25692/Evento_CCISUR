@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">🔍 Buscar Certificados</h2>

    <form method="POST" action="{{ route('certificados.resultado') }}" class="mx-auto" style="max-width: 500px;">
        @csrf
        <div class="mb-3">
            <label for="identidad" class="form-label">Número de Identidad</label>
            <input type="text" class="form-control" id="identidad" name="identidad" placeholder="Ingrese su identidad" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Buscar</button>
    </form>
</div>
@endsection
