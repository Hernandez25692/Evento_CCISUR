@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center mb-4">Editar Capacitación</h1>

    <form action="{{ route('capacitaciones.update', $capacitacion->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nombre de la Capacitación</label>
            <input type="text" class="form-control" name="nombre" value="{{ $capacitacion->nombre }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Lugar</label>
            <input type="text" class="form-control" name="lugar" value="{{ $capacitacion->lugar }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha</label>
            <input type="date" class="form-control" name="fecha" value="{{ $capacitacion->fecha }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Impartido por</label>
            <input type="text" class="form-control" name="impartido_por" value="{{ $capacitacion->impartido_por }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea class="form-control" name="descripcion" rows="3">{{ $capacitacion->descripcion }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Imagen Actual</label>
            <div>
                @if($capacitacion->imagen)
                    <img src="{{ asset('storage/' . $capacitacion->imagen) }}" class="img-fluid" style="max-width: 200px;">
                @else
                    <p>No hay imagen adjunta.</p>
                @endif
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Cambiar Imagen</label>
            <input type="file" class="form-control" name="imagen">
        </div>

        <button type="submit" class="btn btn-success">Guardar Cambios</button>
        <a href="{{ route('capacitaciones.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
