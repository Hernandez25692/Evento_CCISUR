@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center mb-4">Nueva Capacitación</h1>

    <form action="{{ route('capacitaciones.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nombre de la Capacitación</label>
            <input type="text" class="form-control" name="nombre" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Lugar</label>
            <input type="text" class="form-control" name="lugar" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha</label>
            <input type="date" class="form-control" name="fecha" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Impartido por</label>
            <input type="text" class="form-control" name="impartido_por" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea class="form-control" name="descripcion" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Imagen</label>
            <input type="file" class="form-control" name="imagen">
        </div>

        <button type="submit" class="btn btn-success">Guardar Capacitación</button>
        <a href="{{ route('capacitaciones.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
