<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formaciones_CCISUR</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/logo/Logo-CCISUR.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --main-bg: #fff;
            --main-color: #1abc9c;
            --main-dark: #16a085;
            --accent: #2c3e50;
            --text: #222;
            --nav-radius: 18px;
            --nav-shadow: 0 4px 24px rgba(44, 62, 80, 0.08);
            --nav-link-hover: #f6f6f6;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--main-bg);
            color: var(--text);
        }

        .modern-navbar {
            background: var(--main-bg);
            box-shadow: var(--nav-shadow);
            border-radius: 0 0 var(--nav-radius) var(--nav-radius);
            padding: 0.5rem 0;
            position: sticky;
            top: 0;
            z-index: 1040;
        }

        .modern-navbar .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            color: var(--main-color);
            font-size: 1.3rem;
            letter-spacing: 1px;
        }

        .modern-navbar .navbar-brand img {
            height: 38px;
        }

        .modern-navbar .navbar-nav {
            gap: 0.5rem;
        }

        .modern-navbar .nav-link {
            color: var(--accent);
            font-weight: 500;
            border-radius: 8px;
            padding: 0.6rem 1.1rem;
            transition: background .2s, color .2s;
            position: relative;
        }

        .modern-navbar .nav-link.active,
        .modern-navbar .nav-link:hover,
        .modern-navbar .nav-link:focus {
            background: var(--nav-link-hover);
            color: var(--main-color);
        }

        .modern-navbar .dropdown-menu {
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(44, 62, 80, 0.10);
            border: none;
            margin-top: 0.5rem;
            min-width: 180px;
        }

        .modern-navbar .dropdown-item {
            color: var(--accent);
            font-weight: 500;
            border-radius: 6px;
            transition: background .2s, color .2s;
        }

        .modern-navbar .dropdown-item:hover {
            background: var(--main-color);
            color: #fff;
        }

        .modern-navbar .profile-pic {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--main-color);
            margin-right: 8px;
        }

        .modern-navbar .btn-logout {
            border: none;
            background: var(--main-color);
            color: #fff;
            border-radius: 8px;
            padding: 0.5rem 1.2rem;
            font-weight: 600;
            transition: background .2s;
        }

        .modern-navbar .btn-logout:hover {
            background: var(--main-dark);
        }

        @media (max-width: 991.98px) {
            .modern-navbar .navbar-collapse {
                background: var(--main-bg);
                border-radius: 0 0 var(--nav-radius) var(--nav-radius);
                box-shadow: var(--nav-shadow);
                margin-top: 0.5rem;
                padding: 1rem 0.5rem;
            }

            .modern-navbar .navbar-nav {
                gap: 0;
            }

            .modern-navbar .nav-link {
                margin-bottom: 0.5rem;
            }
        }

        .footer-modern {
            background: var(--accent);
            color: #fff;
            text-align: center;
            padding: 2rem 0 1rem 0;
            margin-top: 3rem;
            border-radius: var(--nav-radius) var(--nav-radius) 0 0;
        }

        .footer-modern a {
            color: var(--main-color);
            text-decoration: underline;
            transition: color .2s;
        }

        .footer-modern a:hover {
            color: #fff;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg modern-navbar">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('storage/logo/Logo-CCISUR.png') }}" alt="Logo">
                CCISUR
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"
                    style="filter: invert(60%) sepia(90%) saturate(400%) hue-rotate(120deg);"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('certificados.buscar') ? 'active' : '' }}"
                            href="{{ route('certificados.buscar') }}">
                            <i class="fas fa-search"></i> Certificados
                        </a>
                    </li>
                    @guest
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}"
                                href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('capacitaciones.index') ? 'active' : '' }}"
                                href="{{ route('capacitaciones.index') }}">
                                <i class="fas fa-book"></i> Formaciones
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                                href="{{ route('dashboard') }}">
                                <i class="fas fa-chart-bar"></i> Dashboard 
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('dashboard.filtro') ? 'active' : '' }}"
                                href="{{ route('dashboard.filtro') }}">
                                <i class="fas fa-filter"></i> Filtro Participantes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('reportes.capacitaciones') ? 'active' : '' }}"
                                href="{{ route('reportes.capacitaciones') }}">
                                <i class="fas fa-chart-line"></i> Reportes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('plantillas-globales.index') ? 'active' : '' }}"
                                href="{{ route('plantillas-globales.index') }}">
                                <i class="fas fa-layer-group"></i> Plantillas Globales
                            </a>
                        </li>
                        @if (Auth::check())
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle {{ request()->routeIs('usuarios.index') || request()->routeIs('password.edit') ? 'active' : '' }}"
                                    href="#" id="userDropdown" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    @if (Auth::user()->profile_photo_path)
                                        <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}"
                                            class="profile-pic" alt="Perfil">
                                    @else
                                        <i class="fa fa-user-circle profile-pic"
                                            style="font-size: 1.5rem; background: #e0f7f4; color: var(--main-color);"></i>
                                    @endif
                                    {{ Str::limit(Auth::user()->name, 12) }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li>
                                        <a class="dropdown-item {{ request()->routeIs('usuarios.index') ? 'active' : '' }}"
                                            href="{{ route('usuarios.index') }}">
                                            <i class="fa fa-users"></i> Usuarios
                                        </a>
                                    </li>

                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item btn-logout">
                                                <i class="fa fa-sign-out-alt"></i> Cerrar sesión
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        @yield('content')
    </main>

    <footer class="footer-modern">
        <div class="container">
            <p>&copy; {{ date('Y') }} <strong>Formaciones CCISUR</strong> - Todos los derechos reservados</p>
            <p>
                Desarrollado por
                <a href="mailto:delcarmenhernandez@unah.hn">
                    José Hernández
                </a>
            </p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
