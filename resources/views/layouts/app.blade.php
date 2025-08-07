<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formaciones_CCISUR</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/logo/Logo-CCISUR.png') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Open+Sans:wght@400;600;700&display=swap"
        rel="stylesheet">

    <!-- Estilos Personalizados SOLO para header y footer -->
    <style>
        :root {
            --primary-color: #1abc9c;
            --primary-dark: #16a085;
            --secondary-color: #2c3e50;
            --secondary-light: #34495e;
            --light-color: #ecf0f1;
            --transition: all 0.3s ease;
            --navbar-height: 70px;
        }

        /* ======== ESTILOS EXCLUSIVOS PARA NAVBAR ======== */
        .navbar-custom {
            background-color: var(--secondary-color) !important;
            padding: 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            position: sticky;
            top: 0;
            z-index: 1030;
            font-family: 'Poppins', sans-serif;
            min-height: var(--navbar-height);
        }

        .navbar-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .navbar-brand-custom {
            display: flex;
            align-items: center;
            color: var(--light-color) !important;
            font-weight: 700;
            gap: 12px;
            font-size: 1.25rem;
            padding: 1rem 0;
            transition: var(--transition);
        }

        .navbar-brand-custom img {
            height: 40px;
            width: auto;
            transition: var(--transition);
        }

        .navbar-brand-custom:hover {
            transform: translateY(-2px);
        }

        .navbar-brand-custom:hover img {
            transform: rotate(-5deg) scale(1.05);
        }

        .nav-link-custom {
            color: var(--light-color) !important;
            font-size: 0.95rem;
            font-weight: 500;
            padding: 1rem 1.25rem !important;
            margin: 0 0.1rem;
            border-radius: 0;
            transition: var(--transition);
            position: relative;
            display: flex;
            align-items: center;
            height: var(--navbar-height);
        }

        .nav-link-custom:hover,
        .nav-link-custom:focus {
            color: var(--primary-color) !important;
            background-color: rgba(255, 255, 255, 0.05);
        }

        .nav-link-custom.active {
            color: var(--primary-color) !important;
            font-weight: 600;
            background: linear-gradient(to bottom, rgba(26, 188, 156, 0.1), transparent);
        }

        .nav-link-custom.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background-color: var(--primary-color);
        }

        .nav-link-custom i {
            margin-right: 8px;
            font-size: 1.1em;
            width: 20px;
            text-align: center;
        }

        .navbar-toggler-custom {
            border: none;
            padding: 0.75rem;
            margin-right: 0.5rem;
        }

        .navbar-toggler-custom:focus {
            box-shadow: 0 0 0 3px rgba(26, 188, 156, 0.3);
        }

        .navbar-toggler-icon-custom {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.9%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
            width: 1.5em;
            height: 1.5em;
        }

        /* Dropdown moderno */
        .dropdown-menu-custom {
            background-color: var(--secondary-light);
            border: none;
            border-radius: 0 0 8px 8px;
            margin-top: 0;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .dropdown-item-custom {
            color: var(--light-color);
            padding: 0.75rem 1.5rem;
            transition: var(--transition);
        }

        .dropdown-item-custom:hover {
            background-color: rgba(26, 188, 156, 0.2);
            color: var(--primary-color);
        }

        /* Botón de logout mejorado */
        .btn-logout-custom {
            background-color: transparent;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            border-radius: 6px;
            padding: 0.5rem 1.25rem;
            font-weight: 600;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 1rem;
        }

        .btn-logout-custom:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(26, 188, 156, 0.3);
        }

        .btn-logout-custom i {
            margin-right: 8px;
        }

        /* ======== RESPONSIVIDAD ======== */
        @media (max-width: 992px) {
            .navbar-collapse-custom {
                background-color: var(--secondary-light);
                margin-top: 0;
                padding: 1rem;
                border-radius: 0 0 8px 8px;
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            }

            .nav-item-custom {
                margin: 0.25rem 0;
            }

            .nav-link-custom {
                height: auto;
                padding: 0.75rem 1rem !important;
                border-radius: 4px;
            }

            .nav-link-custom.active::after {
                display: none;
            }

            .btn-logout-custom {
                margin-left: 0;
                margin-top: 1rem;
                width: 100%;
            }
        }

        /* ======== ESTILOS EXCLUSIVOS PARA FOOTER ======== */
        .footer-custom {
            background-color: var(--secondary-color);
            color: var(--light-color);
            text-align: center;
            padding: 1.5rem;
            width: 100%;
            position: relative;
            font-family: 'Poppins', sans-serif;
            margin-top: 2rem;
        }

        .footer-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), #3498db);
        }

        .footer-custom p {
            margin: 0;
            font-size: 0.9rem;
        }

        .footer-custom a {
            color: var(--primary-color);
            text-decoration: none;
            transition: var(--transition);
        }

        .footer-custom a:hover {
            color: var(--light-color);
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <!-- Navbar Customizado - Solo esto tiene nuevos estilos -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container-fluid navbar-container">
            <a class="navbar-brand navbar-brand-custom" href="{{ route('home') }}">
                <img src="{{ asset('storage/logo/Logo-CCISUR.png') }}" alt="CCISUR Logo" class="img-fluid">
                <span class="d-none d-sm-inline">CCISUR</span>
            </a>
            <button class="navbar-toggler navbar-toggler-custom" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon navbar-toggler-icon-custom"></span>
            </button>
            <div class="collapse navbar-collapse navbar-collapse-custom" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item nav-item-custom">
                        <a class="nav-link nav-link-custom {{ request()->routeIs('certificados.buscar') ? 'active' : '' }}"
                            href="{{ route('certificados.buscar') }}">
                            <i class="fas fa-certificate"></i>
                            Buscar Certificados
                        </a>
                    </li>

                    @guest
                        <li class="nav-item nav-item-custom">
                            <a class="nav-link nav-link-custom" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt"></i>
                                Iniciar Sesión
                            </a>
                        </li>
                    @else
                        <li class="nav-item nav-item-custom">
                            <a class="nav-link nav-link-custom {{ request()->routeIs('capacitaciones.index') ? 'active' : '' }}"
                                href="{{ route('capacitaciones.index') }}">
                                <i class="fas fa-book"></i>
                                Formaciones
                            </a>
                        </li>
                        <li class="nav-item nav-item-custom">
                            <a class="nav-link nav-link-custom {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                                href="{{ route('dashboard') }}">
                                <i class="fas fa-chart-bar"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item nav-item-custom">
                            <a class="nav-link nav-link-custom {{ request()->routeIs('dashboard.filtro') ? 'active' : '' }}"
                                href="{{ route('dashboard.filtro') }}">
                                <i class="fas fa-filter"></i>
                                Filtro Participantes
                            </a>
                        </li>
                        <li class="nav-item nav-item-custom">
                            <a class="nav-link nav-link-custom {{ request()->routeIs('reportes.capacitaciones') ? 'active' : '' }}"
                                href="{{ route('reportes.capacitaciones') }}">
                                <i class="fas fa-chart-line"></i>
                                Reportes
                            </a>
                        </li>
                        <li class="nav-item nav-item-custom">
                            <a class="nav-link nav-link-custom {{ request()->routeIs('plantillas-globales.index') ? 'active' : '' }}"
                                href="{{ route('plantillas-globales.index') }}">
                                <i class="fas fa-layer-group"></i>
                                Plantillas Globales
                            </a>
                        </li>

                        <li class="nav-item nav-item-custom ms-lg-2">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-logout-custom px-2 py-1" title="Cerrar Sesión" style="font-size:1rem;min-width:0;width:38px;height:38px;display:flex;align-items:center;justify-content:center;">
                                    <i class="fas fa-sign-out-alt"></i>
                                </button>
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido Dinámico - SIN CAMBIOS (mantiene estilos originales) -->
    <main class="container py-4">
        @yield('content')
    </main>

    <!-- Footer Customizado - Solo esto tiene nuevos estilos -->
    <footer class="footer-custom">
        <div class="container">
            <p>&copy; {{ date('Y') }} <strong>Formaciones CCISUR</strong> - Todos los derechos reservados</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
