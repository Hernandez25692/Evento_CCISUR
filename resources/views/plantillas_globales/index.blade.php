@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 fw-bold text-primary">
                <i class="fas fa-certificate me-2"></i>Plantillas de Diplomas Globales
            </h1>

            <a href="{{ route('plantillas-globales.create') }}" class="btn btn-primary rounded-pill shadow-sm">
                <i class="fas fa-plus-circle me-2"></i>Nueva Plantilla
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle me-2"></i>
                    <span>{{ session('success') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            </div>
        @endif

        <div class="card border-0 shadow-lg">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="mb-0 fw-semibold">
                    <i class="fas fa-list-alt me-2 text-secondary"></i>Listado de Plantillas
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="py-3">#</th>
                                <th class="py-3">Nombre</th>
                                <th class="py-3">Orientación</th>
                                <th class="py-3">Tipo</th>
                                <th class="py-3">Fecha Emisión</th>
                                <th class="py-3 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @forelse ($plantillas as $plantilla)
                                <tr class="align-middle">
                                    <td class="fw-semibold">{{ $plantilla->id }}</td>
                                    <td>
                                        <span class="d-inline-block text-truncate" style="max-width: 200px;"
                                            title="{{ $plantilla->nombre }}">
                                            {{ $plantilla->nombre }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill bg-info bg-opacity-10 text-info">
                                            <i
                                                class="fas fa-{{ $plantilla->orientacion == 'horizontal' ? 'arrows-alt-h' : 'arrows-alt-v' }} me-1"></i>
                                            {{ ucfirst($plantilla->orientacion) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary">
                                            {{ ucfirst($plantilla->tipo_certificado) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-muted">
                                            <i class="far fa-calendar-alt me-1"></i>
                                            {{ \Carbon\Carbon::parse($plantilla->fecha_emision)->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center">
                                            <a href="{{ route('plantillas-globales.edit', $plantilla->id) }}"
                                                class="btn btn-sm btn-outline-warning rounded-pill me-2 px-3"
                                                data-bs-toggle="tooltip" title="Editar">
                                                <i class="fas fa-edit"></i>
                                                <span class="d-none d-md-inline">Editar</span>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                                data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                data-id="{{ $plantilla->id }}" data-nombre="{{ $plantilla->nombre }}"
                                                data-bs-toggle="tooltip" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                                <span class="d-none d-md-inline">Eliminar</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="fas fa-inbox fa-2x mb-3"></i>
                                        <p class="mb-0">No hay plantillas registradas aún.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
           
        </div>
    </div>

    <!-- Modal de confirmación de eliminación -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header border-0">
                        <h5 class="modal-title text-danger" id="deleteModalLabel">
                            <i class="fas fa-exclamation-triangle me-2"></i>Confirmar eliminación
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body py-4">
                        <p class="mb-0">¿Seguro que deseas eliminar la plantilla <strong id="plantillaNombre"
                                class="text-danger"></strong>?</p>
                        <p class="small text-muted mt-2">Esta acción no se puede deshacer.</p>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger rounded-pill px-4">
                            <i class="fas fa-trash me-1"></i>Eliminar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Inicializar tooltips
        document.addEventListener('DOMContentLoaded', function() {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

        // Configuración del modal de eliminación
        const deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const plantillaId = button.getAttribute('data-id');
            const plantillaNombre = button.getAttribute('data-nombre');
            const form = document.getElementById('deleteForm');
            form.action = "{{ url('plantillas-globales') }}/" + plantillaId;
            document.getElementById('plantillaNombre').textContent = plantillaNombre;
        });
    </script>
@endpush
