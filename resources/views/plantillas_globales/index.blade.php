@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Plantillas de Diplomas Globales</h1>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('plantillas-globales.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Nueva Plantilla
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-primary">
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Orientación</th>
                                <th>Tipo</th>
                                <th>Fecha Emisión</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($plantillas as $plantilla)
                                <tr>
                                    <td>{{ $plantilla->id }}</td>
                                    <td>{{ $plantilla->nombre }}</td>
                                    <td>
                                        <span class="badge bg-info text-dark">{{ ucfirst($plantilla->orientacion) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ ucfirst($plantilla->tipo_certificado) }}</span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($plantilla->fecha_emision)->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('plantillas-globales.edit', $plantilla->id) }}"
                                            class="btn btn-sm btn-warning me-1">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal"
                                            data-id="{{ $plantilla->id }}"
                                            data-nombre="{{ $plantilla->nombre }}">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No hay plantillas registradas aún.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal de confirmación de eliminación -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel">Confirmar eliminación</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <p>¿Seguro que deseas eliminar la plantilla <strong id="plantillaNombre"></strong>?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
<script>
    const deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const plantillaId = button.getAttribute('data-id');
        const plantillaNombre = button.getAttribute('data-nombre');
        const form = document.getElementById('deleteForm');
        form.action = "{{ url('plantillas-globales') }}/" + plantillaId;
        document.getElementById('plantillaNombre').textContent = plantillaNombre;
    });
</script>
@endpush
