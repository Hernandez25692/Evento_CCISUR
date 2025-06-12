@extends('layouts.app')

@section('content')
    <div class="certificates-page">
        <!-- Hero Section -->
        <section class="certificates-hero">
            <div class="hero-content">
            <div class="hero-icon-container">
                <i class="fas fa-certificate"></i>
            </div>
            <h1 class="hero-title">Certificados Encontrados</h1>
            <p class="hero-subtitle">Verifica y descarga tus diplomas de capacitación</p>
            
            </div>
        </section>
        <div style="display: flex; justify-content: center; margin-top: 2rem;">
            <a href="{{ route('certificados.buscar') }}" class="download-button" style="max-width:220px;">
                <i class="fas fa-arrow-left"></i> Regresar
            </a>
        </div>
        <div class="certificates-content">
            <!-- Resultado de búsqueda -->
            @if (!$participante)
                <div class="empty-state warning">
                    <div class="empty-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="empty-text">
                        <h3>¡No encontramos resultados!</h3>
                        <p>No se encontró ningún participante con esa identidad.</p>
                    </div>
                </div>
            @else
                <!-- Tarjeta de información del participante - Diseño profesional -->
                <div class="participant-profile" style="background: linear-gradient(120deg, #f8fafc 60%, #e0e7ff 100%); box-shadow: 0 8px 32px rgba(67,97,238,0.10), 0 1.5px 8px rgba(67,97,238,0.06); border: 1.5px solid #e0e7ff; position: relative; overflow: visible;">
                    <div class="profile-avatar" style="position: relative;">
                        <div class="avatar-circle" style="background: linear-gradient(135deg, #4361ee 60%, #4cc9f0 100%); box-shadow: 0 4px 16px rgba(67,97,238,0.18); border: 3px solid #fff;">
                            <i class="fas fa-user" style="font-size: 2.5rem;"></i>
                        </div>
                        <span style="position: absolute; bottom: -10px; right: -10px; background: #38b000; color: #fff; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(56,176,0,0.18); font-size: 1.1rem; border: 2px solid #fff;">
                            <i class="fas fa-check"></i>
                        </span>
                    </div>
                    <div class="profile-info" style="z-index:2; position:relative;">
                        <h2 class="profile-name" style="margin-bottom: 1.25rem; font-size: 2.1rem; font-weight: 800; letter-spacing: -1px; color: #3a0ca3;">
                            {{ $participante->nombre_completo }}
                        </h2>
                        <div class="profile-details" style="gap: 1.25rem;">
                            <div class="detail-item" style="background: linear-gradient(90deg, #e0e7ff 60%, #f8fafc 100%); border-radius: 10px; padding: 1rem 1.25rem; box-shadow: 0 2px 8px rgba(67,97,238,0.07); font-size: 1.05rem;">
                                <i class="fas fa-envelope" style="color: #4361ee;"></i>
                                <span style="word-break: break-all; color: #22223b;">{{ $participante->correo ?: 'No especificado' }}</span>
                            </div>
                            <div class="detail-item" style="background: linear-gradient(90deg, #e0e7ff 60%, #f8fafc 100%); border-radius: 10px; padding: 1rem 1.25rem; box-shadow: 0 2px 8px rgba(67,97,238,0.07); font-size: 1.05rem;">
                                <i class="fas fa-id-card" style="color: #4361ee;"></i>
                                <span style="color: #22223b;">{{ $participante->identidad }}</span>
                            </div>
                            <div class="detail-item" style="background: linear-gradient(90deg, #e0e7ff 60%, #f8fafc 100%); border-radius: 10px; padding: 1rem 1.25rem; box-shadow: 0 2px 8px rgba(67,97,238,0.07); font-size: 1.05rem;">
                                <i class="fas fa-award" style="color: #38b000;"></i>
                                <span style="color: #22223b;">{{ $participante->capacitaciones->count() }} capacitación(es)</span>
                            </div>
                        </div>
                    </div>
                    <div class="profile-badge" style="right: 2rem; top: 50%; transform: translateY(-50%) rotate(-8deg); font-size: 7rem; color: rgba(67,97,238,0.09); z-index: 0;">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <div style="position: absolute; left: -32px; top: -32px; width: 80px; height: 80px; background: radial-gradient(circle at 30% 30%, #4cc9f0 0%, #fff 100%); opacity: 0.18; border-radius: 50%; z-index:0;"></div>
                    <div style="position: absolute; right: -24px; bottom: -24px; width: 60px; height: 60px; background: radial-gradient(circle at 70% 70%, #4361ee 0%, #fff 100%); opacity: 0.13; border-radius: 50%; z-index:0;"></div>
                </div>

                <!-- Listado de capacitaciones -->
                @if ($participante->capacitaciones->isEmpty())
                    <div class="empty-state info">
                        <div class="empty-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div class="empty-text">
                            <h3>Sin capacitaciones registradas</h3>
                            <p>Este participante no tiene capacitaciones registradas aún.</p>
                        </div>
                    </div>
                @else
                    <section class="certificates-section">
                        <h3 class="section-title">
                            <i class="fas fa-list-ul"></i> Tus Diplomas Disponibles
                        </h3>

                        <div class="certificates-grid">
                            @foreach ($participante->capacitaciones as $cap)
                                <article class="certificate-card">
                                    <div class="card-header">
                                        <div class="card-icon">
                                            <i class="fas fa-chalkboard-teacher"></i>
                                        </div>
                                        <span class="card-badge">{{ $loop->iteration }}</span>
                                    </div>

                                    <h4 class="card-title">{{ $cap->nombre }}</h4>

                                    <div class="card-meta">
                                        <div class="meta-item">
                                            <i class="fas fa-calendar-day"></i>
                                            <span>{{ \Carbon\Carbon::parse($cap->fecha_inicio)->isoFormat('D [de] MMMM [de] YYYY') }}</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="fas fa-clock"></i>
                                            <span>{{ $cap->duracion ?: 'Duración no especificada' }}</span>
                                        </div>
                                    </div>

                                    @php
                                        $habilitado = $cap->pivot->habilitado_diploma ?? false;
                                    @endphp

                                    <div class="card-actions">
                                        @if ($habilitado)
                                            <a href="{{ route('certificados.descargar', [$cap->id, $participante->id]) }}"
                                                class="download-button">
                                                <i class="fas fa-download"></i> Descargar Diploma
                                            </a>
                                        @else
                                            <span class="badge bg-warning text-dark px-3 py-2">Diploma no habilitado</span>
                                        @endif
                                    </div>

                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif
            @endif
        </div>
    </div>

    <!-- CDNs -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <style>
        :root {
            --primary: #4361ee;
            --primary-light: #eef2ff;
            --primary-dark: #3a0ca3;
            --secondary: #4cc9f0;
            --success: #38b000;
            --warning: #ff9e00;
            --danger: #ef233c;
            --light: #f8f9fa;
            --dark: #212529;
            --text: #2b2d42;
            --text-light: #8d99ae;
            --gray-100: #f8f9fa;
            --gray-200: #e9ecef;
            --gray-300: #dee2e6;
            --gray-400: #ced4da;
            --gray-500: #adb5bd;
            --gray-600: #6c757d;
            --gray-700: #495057;
            --gray-800: #343a40;
            --gray-900: #212529;
            --border-radius: 12px;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        /* Base Styles */
        .certificates-page {
            min-height: 100vh;
            background-color: var(--gray-100);
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
            color: var(--text);
        }

        /* Hero Section */
        .certificates-hero {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 4rem 1rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .certificates-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%234361ee' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
            opacity: 0.1;
        }

        .hero-content {
            position: relative;
            max-width: 800px;
            margin: 0 auto;
        }

        .hero-icon-container {
            font-size: 3rem;
            margin-bottom: 1.5rem;
            color: rgba(255, 255, 255, 0.9);
        }

        .hero-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: white;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            font-weight: 300;
            opacity: 0.9;
            color: rgba(255, 255, 255, 0.9);
            max-width: 600px;
            margin: 0 auto;
        }

        /* Main Content */
        .certificates-content {
            padding: 2rem 1rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Participant Profile */
        .participant-profile {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-md);
            padding: 2rem;
            margin-bottom: 3rem;
            position: relative;
            overflow: hidden;
            transition: var(--transition);
        }

        .participant-profile:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .profile-avatar {
            flex: 0 0 auto;
            margin-right: 2rem;
        }

        .avatar-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
        }

        .profile-info {
            flex: 1 1 0;
            min-width: 250px;
        }

        .profile-name {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--text);
        }

        .profile-details {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }

        .detail-item {
            display: flex;
            align-items: center;
            font-size: 0.95rem;
        }

        .detail-item i {
            width: 24px;
            color: var(--primary);
            margin-right: 0.75rem;
            font-size: 1rem;
        }

        .profile-badge {
            position: absolute;
            right: 2rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 6rem;
            color: rgba(67, 97, 238, 0.05);
            z-index: 0;
        }

        /* Empty States */
        .empty-state {
            display: flex;
            align-items: center;
            padding: 2rem;
            border-radius: var(--border-radius);
            margin-bottom: 2rem;
            background: white;
            box-shadow: var(--shadow-sm);
        }

        .empty-state.warning {
            background-color: #fff3cd;
            color: #856404;
        }

        .empty-state.info {
            background-color: #e7f5ff;
            color: #0c5460;
        }

        .empty-icon {
            font-size: 2rem;
            margin-right: 1.5rem;
            opacity: 0.8;
        }

        .empty-text h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .empty-text p {
            margin: 0;
            opacity: 0.9;
        }

        /* Certificates Section */
        .certificates-section {
            margin-top: 2rem;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            color: var(--text);
        }

        .section-title i {
            margin-right: 0.75rem;
            color: var(--primary);
        }

        .certificates-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
        }

        /* Certificate Card */
        .certificate-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            padding: 1.5rem;
            transition: var(--transition);
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .certificate-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }

        .card-icon {
            width: 50px;
            height: 50px;
            background-color: var(--primary-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 1.25rem;
        }

        .card-badge {
            background-color: var(--primary);
            color: white;
            border-radius: 20px;
            padding: 0.25rem 0.75rem;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--text);
            line-height: 1.4;
        }

        .card-meta {
            margin-bottom: 1.5rem;
        }

        .meta-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
            font-size: 0.9rem;
            color: var(--text-light);
        }

        .meta-item i {
            width: 20px;
            margin-right: 0.75rem;
            color: var(--primary);
            font-size: 0.9rem;
        }

        .card-actions {
            margin-top: auto;
        }

        .download-button {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 0.75rem;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            transition: var(--transition);
            text-decoration: none;
        }

        .download-button:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-sm);
        }

        .download-button i {
            margin-right: 0.5rem;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .certificates-grid {
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }

            .hero-subtitle {
                font-size: 1.1rem;
            }

            .participant-profile {
                padding: 1.5rem;
            }

            .profile-avatar {
                margin-right: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .avatar-circle {
                width: 70px;
                height: 70px;
                font-size: 1.75rem;
            }

            .profile-name {
                font-size: 1.5rem;
                margin-bottom: 1rem;
            }

            .profile-badge {
                font-size: 4rem;
                right: 1rem;
            }

            .section-title {
                font-size: 1.3rem;
            }
        }

        @media (max-width: 576px) {
            .hero-title {
                font-size: 1.75rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }

            .participant-profile {
                flex-direction: column;
                text-align: center;
            }

            .profile-avatar {
                margin-right: 0;
                margin-bottom: 1.5rem;
            }

            .profile-details {
                grid-template-columns: 1fr;
            }

            .profile-badge {
                position: static;
                transform: none;
                margin-top: 1rem;
                font-size: 3rem;
                text-align: center;
            }

            .certificates-grid {
                grid-template-columns: 1fr;
            }

            .empty-state {
                flex-direction: column;
                text-align: center;
            }

            .empty-icon {
                margin-right: 0;
                margin-bottom: 1rem;
            }
        }
    </style>

    <script>
        // Efecto de aparición gradual para las tarjetas
        document.addEventListener('DOMContentLoaded', function() {
            const animateOnScroll = () => {
                const elements = document.querySelectorAll(
                    '.certificate-card, .participant-profile, .empty-state, .section-title');

                elements.forEach((element, index) => {
                    const elementPosition = element.getBoundingClientRect().top;
                    const windowHeight = window.innerHeight;

                    if (elementPosition < windowHeight - 100) {
                        setTimeout(() => {
                            element.style.opacity = '1';
                            element.style.transform = 'translateY(0)';
                        }, index * 100);
                    }
                });
            };

            // Establecer estado inicial
            const cards = document.querySelectorAll('.certificate-card');
            const profile = document.querySelector('.participant-profile');
            const emptyStates = document.querySelectorAll('.empty-state');
            const sectionTitle = document.querySelector('.section-title');

            cards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            });

            if (profile) {
                profile.style.opacity = '0';
                profile.style.transform = 'translateY(20px)';
                profile.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            }

            emptyStates.forEach(state => {
                state.style.opacity = '0';
                state.style.transform = 'translateY(20px)';
                state.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            });

            if (sectionTitle) {
                sectionTitle.style.opacity = '0';
                sectionTitle.style.transform = 'translateY(20px)';
                sectionTitle.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            }

            // Ejecutar al cargar y al hacer scroll
            animateOnScroll();
            window.addEventListener('scroll', animateOnScroll);
        });
    </script>
@endsection
