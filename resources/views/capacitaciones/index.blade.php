@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="text-center mb-4">Lista de Formaciones</h1>
    <a href="{{ route('capacitaciones.create') }}" class="btn btn-primary mb-3">➕ Nueva Capacitación</a>

    <div class="row">
        @foreach($capacitaciones as $capacitacion)
        <div class="col-md-4 mb-4">
            <div class="card shadow border-0 rounded h-100 position-relative">
                @if($capacitacion->imagen)
                    <img src="{{ asset('storage/' . $capacitacion->imagen) }}" class="card-img-top custom-img" alt="Imagen de la capacitación">
                @else
                    <img src="{{ asset('images/default.png') }}" class="card-img-top custom-img" alt="Imagen por defecto">
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-primary">{{ $capacitacion->nombre }}</h5>
                    <p class="card-text"><strong>Lugar:</strong> {{ $capacitacion->lugar }}</p>
                    <p class="card-text"><strong>Fecha:</strong> {{ $capacitacion->fecha }}</p>

                    <!-- Botón para mostrar menú -->
                    <button class="btn btn-info mt-auto w-100 toggle-slide" data-slide="slideMenu{{ $capacitacion->id }}">
                        🔍 Ver Detalles
                    </button>

                    <!-- Menú deslizable dentro de la tarjeta -->
                    <div class="slide-menu mt-3" id="slideMenu{{ $capacitacion->id }}">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <a href="{{ route('capacitaciones.edit', $capacitacion->id) }}" class="text-decoration-none">
                                    ✏️ Editar evento
                                </a>
                            </li>
                            <li class="list-group-item">
                                <a href="{{ route('capacitaciones.participantes', $capacitacion->id) }}" class="text-decoration-none">
                                    👥 Listar participantes
                                </a>
                            </li>
                            <li class="list-group-item">
                                <a href="{{ route('capacitaciones.participantes.create', $capacitacion->id) }}" class="text-decoration-none">
                                    ➕ Agregar participante
                                </a>
                            </li>
                            <li class="list-group-item">
                                <a href="{{ route('capacitaciones.plantilla', $capacitacion->id) }}" class="text-decoration-none">
                                    📄 Agregar plantilla
                                </a>
                            </li>
                            <li class="list-group-item">
                                <a href="{{ route('capacitaciones.diplomas', $capacitacion->id) }}" class="text-decoration-none">
                                    🎓 Generar diplomas
                                </a>
                            </li>
                            <li class="list-group-item text-danger">
                                <button class="btn btn-danger w-100" onclick="confirmarEliminacion({{ $capacitacion->id }})">
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
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const botones = document.querySelectorAll(".toggle-slide");

        botones.forEach(btn => {
            btn.addEventListener("click", function (e) {
                const targetId = this.getAttribute("data-slide");
                const menu = document.getElementById(targetId);

                // Cierra todos los demás
                document.querySelectorAll(".slide-menu").forEach(m => {
                    if (m.id !== targetId) {
                        m.classList.remove("slide-open");
                    }
                });

                // Alterna este
                menu.classList.toggle("slide-open");
                e.stopPropagation();
            });
        });

        // Ocultar menú al hacer clic fuera
        document.addEventListener("click", function (e) {
            if (!e.target.closest(".slide-menu") && !e.target.classList.contains("toggle-slide")) {
                document.querySelectorAll(".slide-menu").forEach(menu => {
                    menu.classList.remove("slide-open");
                });
            }
        });
    });

    function confirmarEliminacion(id) {
        if (confirm('¿Estás seguro de que deseas eliminar este evento? Esta acción no se puede deshacer.')) {
            document.getElementById('eliminar-capacitacion-' + id).submit();
        }
    }
</script>

<style>
    .custom-img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .card:hover {
        transform: scale(1.02);
        transition: transform 0.3s ease;
    }

    .btn-info {
        background-color: #007bff;
        border: none;
    }

    .btn-info:hover {
        background-color: #0056b3;
    }

    .slide-menu {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
        background-color: #f8f9fa;
        border-radius: 8px;
    }

    .slide-menu.slide-open {
        max-height: 1000px;
        transition: max-height 0.5s ease;
    }

    .list-group-item {
        font-size: 0.95rem;
    }

    .list-group-item a {
        color: #0d6efd;
    }

    .list-group-item:hover {
        background-color: #e9ecef;
    }
</style>
@endsection
