@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center mb-4">Agregar Plantilla de Diploma</h1>

    <form action="{{ route('capacitaciones.plantilla.store', $capacitacion->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="mb-3">
            <label class="form-label">Fecha de Emisión</label>
            <input type="date" class="form-control" name="fecha_emision" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Subir Firma</label>
            <input type="file" class="form-control" name="firma" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Subir Fondo del Diploma</label>
            <input type="file" class="form-control" name="fondo" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Orientación del Diploma</label>
            <select class="form-control" name="orientacion" required>
                <option value="horizontal" selected>Horizontal</option>
                <option value="vertical">Vertical</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Guardar Plantilla</button>
        <a href="{{ route('capacitaciones.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
