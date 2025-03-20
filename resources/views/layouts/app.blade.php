<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evento_CCISUR</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Iconos de FontAwesome -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    
    <!-- Estilos Personalizados -->
    <style>
        /* ======== NAVBAR ======== */
        .navbar {
            background-color: #2c3e50 !important; /* Color más elegante */
            padding: 10px 15px;
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            color: white !important;
            font-weight: bold;
            gap: 10px;
        }
        .navbar-brand img {
            height: 40px;
            max-width: 100%;
        }
        .nav-link {
            color: white !important;
            font-size: 16px;
            font-weight: 500;
        }
        .nav-link:hover {
            color: #1abc9c !important; /* Color destacado */
            transition: 0.3s;
        }

        /* ======== CONTENEDOR PRINCIPAL ======== */
        .container {
            max-width: 1200px;
            padding-top: 20px;
        }

        /* ======== FOOTER ======== */
        .footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: 30px;
            width: 100%;
        }

        /* ======== BOTONES ======== */
        .btn-custom {
            background-color: #1abc9c;
            color: white;
            border-radius: 8px;
            padding: 8px 15px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            background-color: #16a085;
            transform: scale(1.05);
        }

        /* ======== RESPONSIVE ======== */
        @media (max-width: 768px) {
            .navbar-nav {
                text-align: center;
            }
            .footer {
                position: relative;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar Responsivo -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <!-- Logo de la CCISUR -->
                <img src="{{ asset('storage/logo/Logo-CCISUR.png') }}" alt="CCISUR Logo">
                Evento_CCISUR
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('capacitaciones.create') }}">
                            <i class="fas fa-plus-circle"></i> Nueva Capacitación
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="fas fa-chart-bar"></i> Dashboard
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido Dinámico -->
    <div class="container">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; {{ date('Y') }} Evento_CCISUR - Todos los derechos reservados</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
