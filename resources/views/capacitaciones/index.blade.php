@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="text-center mb-4">Lista de Capacitaciones</h1>
    <a href="{{ route('capacitaciones.create') }}" class="btn btn-primary mb-3">➕ Nueva Capacitación</a>

    <div class="row">
        @foreach($capacitaciones as $capacitacion)
        <div class="col-md-4 mb-4">
            <div class="card shadow-lg border-0 rounded">
                @if($capacitacion->imagen)
                    <img src="{{ asset('storage/' . $capacitacion->imagen) }}" class="card-img-top custom-img" alt="Imagen de la capacitación">
                @else
                    <img src="{{ asset('images/default.png') }}" class="card-img-top custom-img" alt="Imagen por defecto">
                @endif
                <div class="card-body">
                    <h5 class="card-title text-primary">{{ $capacitacion->nombre }}</h5>
                    <p class="card-text"><strong>Lugar:</strong> {{ $capacitacion->lugar }}</p>
                    <p class="card-text"><strong>Fecha:</strong> {{ $capacitacion->fecha }}</p>

                    <!-- Botón para abrir el menú offcanvas -->
                    <button class="btn btn-info w-100" type="button" data-bs-toggle="offcanvas" 
                        data-bs-target="#offcanvasDetalles{{ $capacitacion->id }}" 
                        aria-controls="offcanvasDetalles{{ $capacitacion->id }}">
                        🔍 Ver Detalles
                    </button>

                    <!-- Offcanvas (Menú lateral en escritorio, inferior en móvil) -->
                    <div class="offcanvas offcanvas-end offcanvas-responsive" tabindex="-1" 
                        id="offcanvasDetalles{{ $capacitacion->id }}">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title">Detalles de {{ $capacitacion->nombre }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
                        </div>
                        <div class="offcanvas-body">
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <a href="{{ route('capacitaciones.edit', $capacitacion->id) }}" class="text-decoration-none close-offcanvas">
                                        ✏️ Editar evento
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <a href="{{ route('capacitaciones.participantes', $capacitacion->id) }}" class="text-decoration-none close-offcanvas">
                                        👥 Listar participantes
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <a href="{{ route('capacitaciones.participantes.create', $capacitacion->id) }}" class="text-decoration-none close-offcanvas">
                                        ➕ Agregar participante
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <a href="{{ route('capacitaciones.plantilla', $capacitacion->id) }}" class="text-decoration-none close-offcanvas">
                                        📄 Agregar plantilla
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <a href="{{ route('capacitaciones.diplomas', $capacitacion->id) }}" class="text-decoration-none close-offcanvas">
                                        🎓 Generar diplomas
                                    </a>
                                </li>
                                <li class="list-group-item text-danger">
                                    <button class="btn btn-danger w-100 close-offcanvas" onclick="confirmarEliminacion({{ $capacitacion->id }})">
                                        🗑️ Eliminar evento
                                    </button>
                                    <form id="eliminar-capacitacion-{{ $capacitacion->id }}" 
                                        action="{{ route('capacitaciones.destroy', $capacitacion->id) }}" 
                                        method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- Fin del Offcanvas -->
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
    function confirmarEliminacion(id) {
        if (confirm('¿Estás seguro de que deseas eliminar este evento? Esta acción no se puede deshacer.')) {
            document.getElementById('eliminar-capacitacion-' + id).submit();
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        // Cierra el menú cuando se hace clic en cualquier opción
        document.querySelectorAll(".close-offcanvas").forEach(item => {
            item.addEventListener("click", function() {
                let offcanvas = this.closest(".offcanvas");
                let bsOffcanvas = bootstrap.Offcanvas.getInstance(offcanvas);
                bsOffcanvas.hide();
            });
        });

        // Ajustar el menú según el tamaño de la pantalla
        function ajustarOffcanvas() {
            document.querySelectorAll(".offcanvas-responsive").forEach(offcanvas => {
                if (window.innerWidth < 768) {
                    offcanvas.classList.remove("offcanvas-end");
                    offcanvas.classList.add("offcanvas-bottom");
                } else {
                    offcanvas.classList.remove("offcanvas-bottom");
                    offcanvas.classList.add("offcanvas-end");
                }
            });
        }

        // Ejecutar cuando se cargue la página y al cambiar el tamaño de la ventana
        ajustarOffcanvas();
        window.addEventListener("resize", ajustarOffcanvas);
    });
</script>

<style>
    .custom-img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: scale(1.05);
        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
    }

    .offcanvas {
        width: 300px; /* Define el ancho del menú */
    }

    .offcanvas-bottom {
        height: 50vh; /* Altura en móviles */
    }

    .list-group-item a {
        color: #007bff;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
    }

    .btn-info {
        background-color: #007bff;
        border: none;
    }

    .btn-info:hover {
        background-color: #0056b3;
    }
</style>

@endsection
