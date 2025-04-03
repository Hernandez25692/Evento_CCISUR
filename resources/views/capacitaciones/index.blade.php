@extends('layouts.app')

@section('content')
<div class="training-list-container">
    <!-- Encabezado con efecto -->
    <div class="page-header">
        <div class="header-content">
            <h1>
                <i class="fas fa-book-open"></i>
                Lista de Formaciones
            </h1>
            <p>Gestiona todas tus actividades de capacitación</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('capacitaciones.create') }}" class="create-btn">
                <i class="fas fa-plus-circle"></i> Nueva Formación
            </a>
        </div>
    </div>

    <!-- Contenido principal -->
    <div class="training-grid">
        @foreach($capacitaciones as $capacitacion)
        <div class="training-card">
            <!-- Imagen de la formación -->
            <div class="card-image">
                @if($capacitacion->imagen)
                    <img src="{{ asset('storage/' . $capacitacion->imagen) }}" alt="Imagen de la capacitación">
                @else
                    <img src="{{ asset('images/default.png') }}" alt="Imagen por defecto">
                @endif
                <div class="image-overlay"></div>
            </div>

            <!-- Contenido de la tarjeta -->
            <div class="card-content">
                <h3>{{ $capacitacion->nombre }}</h3>
                
                <div class="card-details">
    <div class="detail-item">
        <i class="fas fa-map-marker-alt"></i>
        <span>{{ $capacitacion->lugar }}</span>
    </div>
    <div class="detail-item">
        <i class="fas fa-calendar-alt"></i>
        <span>{{ $capacitacion->fecha }}</span>
    </div>
    <div class="detail-item">
        <i class="fas fa-broadcast-tower"></i> <!-- Icono para medio -->
        <span>{{ $capacitacion->medio }}</span>
    </div>
    <div class="detail-item">
        <i class="fas fa-chalkboard-teacher"></i> <!-- Icono para instructor -->
        <span>{{ $capacitacion->impartido_por }}</span>
    </div>
    <div class="detail-item">
        <i class="fas fa-graduation-cap"></i> <!-- Icono para tipo de formación -->
        <span>{{ $capacitacion->tipo_formacion }}</span>
    </div>
</div>

                <!-- Botón de acciones -->
                <button class="action-toggle" data-target="menu-{{ $capacitacion->id }}">
                    <i class="fas fa-ellipsis-h"></i> Acciones
                </button>

                <!-- Menú desplegable -->
                <div class="action-menu" id="menu-{{ $capacitacion->id }}">
                    <ul>
                        <li>
                            <a href="{{ route('capacitaciones.edit', $capacitacion->id) }}">
                                <i class="fas fa-edit"></i> Editar evento
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('capacitaciones.participantes', $capacitacion->id) }}">
                                <i class="fas fa-users"></i> Listar participantes
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('capacitaciones.participantes.create', $capacitacion->id) }}">
                                <i class="fas fa-user-plus"></i> Agregar participante
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('capacitaciones.plantilla', $capacitacion->id) }}">
                                <i class="fas fa-file-alt"></i> Configurar diploma
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('capacitaciones.diplomas', $capacitacion->id) }}">
                                <i class="fas fa-certificate"></i> Generar diplomas
                            </a>
                        </li>
                        <li class="delete-action">
                            <button onclick="confirmarEliminacion({{ $capacitacion->id }})">
                                <i class="fas fa-trash-alt"></i> Eliminar evento
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
        @endforeach
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Toggle para menús de acción
        const toggles = document.querySelectorAll(".action-toggle");
        
        toggles.forEach(toggle => {
            toggle.addEventListener("click", function(e) {
                const targetId = this.getAttribute("data-target");
                const menu = document.getElementById(targetId);
                
                // Cerrar otros menús abiertos
                document.querySelectorAll(".action-menu").forEach(m => {
                    if(m.id !== targetId) m.classList.remove("active");
                });
                
                // Toggle menú actual
                menu.classList.toggle("active");
                e.stopPropagation();
            });
        });
        
        // Cerrar menús al hacer clic fuera
        document.addEventListener("click", function() {
            document.querySelectorAll(".action-menu").forEach(menu => {
                menu.classList.remove("active");
            });
        });

        // Confirmación de eliminación para todos los botones de eliminar
        document.querySelectorAll('.delete-action button').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const formId = this.closest('.delete-action').querySelector('form').id;
                
                Swal.fire({
                    title: '¿Eliminar formación?',
                    text: "Esta acción no se puede deshacer",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(formId).submit();
                    }
                });
            });
        });
    });
</script>

<!-- CDNs -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<style>
    /* Variables de diseño */
    :root {
        --primary-color: #4361ee;
        --primary-light: #6c7ef0;
        --primary-dark: #3a56d4;
        --success-color: #28a745;
        --danger-color: #dc3545;
        --text-color: #333;
        --light-gray: #f8f9fa;
        --medium-gray: #e9ecef;
        --dark-gray: #6c757d;
        --white: #ffffff;
        --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s ease;
    }

    /* Estructura principal */
    .training-list-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1.5rem;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Encabezado de página */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--medium-gray);
    }

    .header-content h1 {
        font-size: 2rem;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
        font-weight: 700;
    }

    .header-content h1 i {
        margin-right: 0.75rem;
    }

    .header-content p {
        color: var(--dark-gray);
        font-size: 1.1rem;
        margin: 0;
    }

    /* Botón de creación */
    .create-btn {
        display: inline-flex;
        align-items: center;
        background: var(--primary-color);
        color: var(--white);
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 500;
        transition: var(--transition);
        box-shadow: var(--shadow);
    }

    .create-btn i {
        margin-right: 0.5rem;
    }

    .create-btn:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        color: var(--white);
    }

    /* Grid de formaciones */
    .training-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
    }

    /* Tarjetas de formación */
    .training-card {
        background: var(--white);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--shadow);
        transition: var(--transition);
        position: relative;
    }

    .training-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    /* Imagen de la tarjeta */
    .card-image {
        position: relative;
        height: 200px;
        overflow: hidden;
    }

    .card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition);
    }

    .training-card:hover .card-image img {
        transform: scale(1.05);
    }

    .image-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 40%;
        background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
    }

    /* Contenido de la tarjeta */
    .card-content {
        padding: 1.5rem;
    }

    .card-content h3 {
        margin: 0 0 1rem 0;
        color: var(--primary-color);
        font-size: 1.3rem;
        font-weight: 600;
    }

    /* Detalles de la formación */
    .card-details {
        margin-bottom: 1.5rem;
    }

    .detail-item {
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
        color: var(--text-color);
    }

    .detail-item i {
        margin-right: 0.75rem;
        color: var(--primary-color);
        width: 20px;
        text-align: center;
    }

    /* Botón de acciones */
    .action-toggle {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 0.75rem;
        background: var(--light-gray);
        border: none;
        border-radius: 8px;
        color: var(--primary-color);
        font-weight: 500;
        cursor: pointer;
        transition: var(--transition);
    }

    .action-toggle i {
        margin-right: 0.5rem;
    }

    .action-toggle:hover {
        background: rgba(67, 97, 238, 0.1);
    }

    /* Menú de acciones */
    .action-menu {
        position: absolute;
        width: calc(100% - 3rem);
        left: 1.5rem;
        bottom: 1.5rem;
        background: var(--white);
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        max-height: 0;
        opacity: 0;
        transition: all 0.3s ease;
        z-index: 10;
    }

    .action-menu.active {
        max-height: 500px;
        opacity: 1;
    }

    .action-menu ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .action-menu li {
        border-bottom: 1px solid var(--medium-gray);
    }

    .action-menu li:last-child {
        border-bottom: none;
    }

    .action-menu a, .action-menu button {
        display: flex;
        align-items: center;
        width: 100%;
        padding: 0.75rem 1rem;
        text-decoration: none;
        color: var(--text-color);
        background: none;
        border: none;
        text-align: left;
        cursor: pointer;
        transition: var(--transition);
    }

    .action-menu a:hover, .action-menu button:hover {
        background: var(--light-gray);
        color: var(--primary-color);
    }

    .action-menu i {
        margin-right: 0.75rem;
        width: 20px;
        text-align: center;
    }

    .delete-action button {
        color: var(--danger-color) !important;
    }

    .delete-action button:hover {
        background: rgba(220, 53, 69, 0.1) !important;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .header-actions {
            margin-top: 1.5rem;
            width: 100%;
        }
        
        .create-btn {
            width: 100%;
            justify-content: center;
        }
        
        .training-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .training-list-container {
            padding: 1.5rem 1rem;
        }
        
        .header-content h1 {
            font-size: 1.6rem;
        }
        
        .card-content {
            padding: 1rem;
        }
        
        .action-menu {
            width: calc(100% - 2rem);
            left: 1rem;
        }
    }
</style>
@endsection