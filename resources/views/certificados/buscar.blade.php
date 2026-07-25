<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Verificación de Diplomas | CCISUR - Cámara de Comercio e Industrias del Sur</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Consulta y descarga tus diplomas de capacitación emitidos por la Cámara de Comercio e Industrias del Sur (CCISUR).">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --navy: #071f46;
            --navy-2: #293987;
            --gold: #c8a526;
            --gold-light: #f4cf57;
            --primary: var(--navy);
            --primary-dark: #051633;
            --primary-light: #e9ecf6;
            --text: #202538;
            --text-light: #6b7185;
            --white: #ffffff;
            --gray: #f7f8fb;
            --error: #ef233c;
            --border-radius: 12px;
            --box-shadow: 0 8px 24px rgba(7, 31, 70, 0.08);
            --transition: all 0.25s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text);
            background-color: var(--gray);
            line-height: 1.6;
        }

        .app-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ============ HEADER INSTITUCIONAL ============ */
        .app-header {
            position: relative;
            background:
                radial-gradient(circle at 14% 20%, rgba(244, 207, 87, 0.16), transparent 30%),
                radial-gradient(circle at 90% 0%, rgba(255, 255, 255, 0.08), transparent 24%),
                linear-gradient(135deg, var(--navy) 0%, var(--navy-2) 100%);
            color: var(--white);
            padding: 1.35rem 0;
            box-shadow: 0 4px 18px rgba(7, 31, 70, 0.25);
            border-bottom: 3px solid var(--gold);
            overflow: hidden;
        }

        .header-inner {
            display: flex;
            align-items: center;
            gap: 1.1rem;
            flex-wrap: wrap;
        }

        .header-logo {
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.96);
            border-radius: 14px;
            padding: .4rem .6rem;
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.18);
        }

        .header-logo img {
            display: block;
            height: 42px;
            width: auto;
        }

        .header-titles {
            flex: 1;
            min-width: 180px;
        }

        .header-titles h1 {
            font-size: 1.3rem;
            font-weight: 700;
            letter-spacing: .2px;
            margin: 0;
        }

        .header-titles p {
            font-size: .85rem;
            opacity: .85;
            font-weight: 300;
            margin: .1rem 0 0;
        }

        .header-contact {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            gap: .6rem;
            flex-wrap: wrap;
        }

        .header-contact a {
            display: inline-flex;
            align-items: center;
            gap: .5em;
            text-decoration: none;
            font-size: .85rem;
            font-weight: 500;
            color: #fff;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 999px;
            padding: .5em 1em;
            transition: var(--transition);
            white-space: nowrap;
        }

        .header-contact a:hover {
            background: rgba(255, 255, 255, 0.22);
            transform: translateY(-1px);
        }

        .header-contact a.whatsapp i {
            color: #25D366;
        }

        .header-contact a.website i {
            color: var(--gold-light);
        }

        @media (max-width: 700px) {
            .header-inner {
                justify-content: center;
                text-align: center;
            }

            .header-titles {
                flex-basis: 100%;
                text-align: center;
            }

            .header-contact {
                flex-basis: 100%;
                justify-content: center;
            }
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 3rem 0;
            display: flex;
            align-items: center;
        }

        .certificate-card {
            background-color: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            overflow: hidden;
            transition: var(--transition);
            border-top: 3px solid var(--gold);
        }

        .certificate-header {
            background: linear-gradient(135deg, var(--navy) 0%, var(--navy-2) 100%);
            color: var(--white);
            padding: 1.75rem 1.5rem;
            text-align: center;
        }

        .certificate-icon img {
            max-width: 110px;
            max-height: 110px;
        }

        .certificate-title {
            font-size: 1.6rem;
            font-weight: 700;
            margin: .75rem 0 .35rem;
        }

        .certificate-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            font-weight: 300;
        }

        .certificate-body {
            padding: 2rem;
        }

        .form-label {
            font-weight: 500;
            color: var(--navy);
            margin-bottom: 0.5rem;
        }

        .input-group-text {
            background-color: var(--primary-light);
            color: var(--navy);
            border: none;
        }

        .form-control {
            border: 1px solid #e0e0e0;
            border-radius: var(--border-radius);
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-control:focus {
            border-color: var(--navy-2);
            box-shadow: 0 0 0 3px rgba(41, 57, 135, 0.15);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-light) 100%);
            color: var(--navy);
            border: none;
            border-radius: var(--border-radius);
            padding: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            transition: var(--transition);
            width: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(200, 165, 38, 0.35);
            color: var(--navy);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-loading .spinner {
            margin-left: 0.5rem;
        }

        .info-text {
            font-size: 0.9rem;
            color: var(--text-light);
            text-align: center;
            margin-top: 1.5rem;
        }

        .info-text i {
            color: var(--navy-2);
            margin-right: 0.5rem;
        }

        .help-link {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            color: var(--navy-2);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .help-link:hover {
            color: var(--navy);
            text-decoration: underline;
        }

        /* Modal */
        .modal-content {
            border-radius: var(--border-radius);
            border: none;
            overflow: hidden;
        }

        .modal-header {
            background: linear-gradient(135deg, var(--navy) 0%, var(--navy-2) 100%);
            color: var(--white);
            border-bottom: none;
            padding: 1.5rem;
        }

        .modal-title {
            font-weight: 600;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .help-item {
            margin-bottom: 1.5rem;
        }

        .help-item:last-child {
            margin-bottom: 0;
        }

        .help-item-title {
            font-weight: 600;
            color: var(--navy);
            margin-bottom: 0.5rem;
        }

        .contact-link {
            color: var(--navy-2);
            text-decoration: none;
        }

        .contact-link:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .certificate-title {
                font-size: 1.4rem;
            }

            .certificate-body {
                padding: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .main-content {
                padding: 1.5rem 0;
            }

            .certificate-header {
                padding: 1.25rem 1rem;
            }

            .certificate-title {
                font-size: 1.25rem;
            }

            .certificate-body {
                padding: 1.25rem;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }

        /* Validation */
        .is-invalid {
            border-color: var(--error) !important;
        }

        .invalid-feedback {
            color: var(--error);
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }

        /* ============ BOTÓN FLOTANTE WHATSAPP ============ */
        .whatsapp-float {
            position: fixed;
            bottom: 22px;
            right: 22px;
            width: 58px;
            height: 58px;
            border-radius: 50%;
            background: #25D366;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            text-decoration: none;
            box-shadow: 0 8px 24px rgba(37, 211, 102, 0.45);
            z-index: 999;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .whatsapp-float:hover {
            transform: scale(1.08);
            box-shadow: 0 10px 28px rgba(37, 211, 102, 0.55);
            color: #fff;
        }

        @media (max-width: 480px) {
            .whatsapp-float {
                width: 52px;
                height: 52px;
                font-size: 1.4rem;
                bottom: 16px;
                right: 16px;
            }
        }

        /* ============ FOOTER INSTITUCIONAL CCISUR ============ */
        .ccisur-footer {
            --footer-blue: #071f46;
            --footer-blue-2: #293987;
            --footer-gold: #c8a526;
            --footer-gold-light: #f4cf57;
            --footer-text: rgba(255, 255, 255, 0.82);
            --footer-muted: rgba(255, 255, 255, 0.64);
            margin-top: 0;
            padding: 60px 6% 28px;
            background:
                radial-gradient(circle at 12% 16%, rgba(244, 207, 87, 0.18), transparent 26%),
                radial-gradient(circle at 88% 12%, rgba(255, 255, 255, 0.08), transparent 22%),
                linear-gradient(135deg, var(--footer-blue), var(--footer-blue-2));
            color: #ffffff;
            overflow: hidden;
        }

        .ccisur-footer * {
            box-sizing: border-box;
        }

        .ccisur-footer-container {
            width: min(1180px, 100%);
            margin: 0 auto;
        }

        .ccisur-footer-grid {
            display: grid;
            grid-template-columns: 1.35fr 0.85fr 1.15fr 0.95fr;
            gap: 34px;
            align-items: start;
        }

        .ccisur-footer-logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 150px;
            min-height: 86px;
            padding: 12px;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.18);
            margin-bottom: 20px;
        }

        .ccisur-footer-logo img {
            max-width: 100%;
            height: auto;
            display: block;
        }

        .ccisur-footer h3 {
            margin: 0 0 16px;
            color: #ffffff;
            font-size: 1.18rem;
            font-weight: 900;
            line-height: 1.3;
        }

        .ccisur-footer-brand p,
        .ccisur-footer-column p {
            margin: 0 0 12px;
            color: var(--footer-text);
            line-height: 1.65;
            font-size: 0.96rem;
        }

        .ccisur-footer-column strong {
            color: var(--footer-gold-light);
        }

        .ccisur-footer-column ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            gap: 10px;
        }

        .ccisur-footer-column ul li a {
            color: var(--footer-text) !important;
            text-decoration: none !important;
            font-weight: 700;
            transition: all 0.25s ease;
        }

        .ccisur-footer-column ul li a:hover {
            color: var(--footer-gold-light) !important;
            padding-left: 5px;
        }

        .ccisur-social-icons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .ccisur-social-icons a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            padding: 0;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.10);
            border: 1px solid rgba(255, 255, 255, 0.16);
            color: #ffffff !important;
            text-decoration: none !important;
            transition: all 0.25s ease;
        }

        .ccisur-social-icons a svg {
            width: 18px;
            height: 18px;
            display: block;
            fill: currentColor;
        }

        .ccisur-social-icons a:hover {
            background: linear-gradient(135deg, var(--footer-gold), var(--footer-gold-light));
            color: var(--footer-blue) !important;
            transform: translateY(-2px);
        }

        .ccisur-footer-bottom {
            margin-top: 46px;
            padding-top: 24px;
            border-top: 1px solid rgba(255, 255, 255, 0.14);
            display: flex;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .ccisur-footer-bottom p {
            margin: 0;
            color: var(--footer-muted);
            font-size: 0.9rem;
        }

        @media (max-width: 1024px) {
            .ccisur-footer-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .ccisur-footer {
                padding: 54px 5% 26px;
            }

            .ccisur-footer-grid {
                grid-template-columns: 1fr;
                gap: 28px;
            }

            .ccisur-footer-logo {
                width: 132px;
            }

            .ccisur-footer-bottom {
                flex-direction: column;
            }
        }
    </style>
</head>

<body class="app-container">
    <!-- Header -->
    <header class="app-header">
        <div class="container header-inner">
            <a href="https://www.ccisur.org/" target="_blank" rel="noopener" class="header-logo">
                <img src="{{ asset('storage/logo_diploma/' . rawurlencode('Logo_Diploma.png')) }}"
                    alt="CCISUR - Cámara de Comercio e Industrias del Sur">
            </a>
            <div class="header-titles">
                <h1>Verificación de Diplomas</h1>
                <p>Cámara de Comercio e Industrias del Sur</p>
            </div>
            <div class="header-contact">
                <a href="https://wa.me/50433150844" target="_blank" rel="noopener" class="whatsapp">
                    <i class="fab fa-whatsapp"></i> +504 3315-0844
                </a>
                <a href="https://www.ccisur.org/" target="_blank" rel="noopener" class="website">
                    <i class="fas fa-globe"></i> ccisur.org
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container animate-fade-in">
            <div class="certificate-card">
                <div class="certificate-header">
                    <div class="certificate-icon">
                        <img src="{{ asset('storage/logo_diploma/' . rawurlencode('Logo_Diploma.png')) }}"
                            alt="Logo CCISUR">
                    </div>
                    <h1 class="certificate-title">Verificación de Diplomas</h1>
                    <p class="certificate-subtitle">Consulta y descarga tus diplomas de capacitación</p>
                </div>

                <div class="certificate-body">
                    <form method="POST" action="{{ route('certificados.resultado') }}" id="certSearchForm" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label for="identidad" class="form-label">Número de Identidad</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                <input type="text" class="form-control @error('identidad') is-invalid @enderror"
                                    id="identidad" name="identidad" placeholder="Ejemplo: 0801199912345" required
                                    autocomplete="off" autofocus maxlength="13" pattern="\d{13}" inputmode="numeric">
                            </div>
                            @error('identidad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary" id="certBtn">
                            <span>Buscar Certificados</span>
                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>

                        <p class="info-text">
                            <i class="fas fa-info-circle"></i>
                            Ingresa tu número de identidad completo (13 dígitos, sin guiones).
                        </p>

                        <a href="#" class="help-link" data-bs-toggle="modal" data-bs-target="#helpModal">
                            <i class="fas fa-question-circle"></i> ¿Necesitas ayuda?
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <a href="https://wa.me/50433150844" target="_blank" rel="noopener" class="whatsapp-float"
        title="Escríbenos por WhatsApp" aria-label="Escríbenos por WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- Footer -->
    <footer class="ccisur-footer">
        <div class="ccisur-footer-container">
            <div class="ccisur-footer-grid">
                <div class="ccisur-footer-brand">
                    <a class="ccisur-footer-logo" href="https://www.ccisur.org/" target="_blank" rel="noopener">
                        <img src="{{ asset('storage/logo_diploma/' . rawurlencode('Logo_Diploma.png')) }}"
                            alt="CCISUR - Cámara de Comercio e Industrias del Sur" loading="lazy" decoding="async">
                    </a>
                    <h3>Cámara de Comercio e Industrias del Sur</h3>
                    <p>
                        Institución facilitadora, promotora y defensora de la libre empresa, impulsando el desarrollo
                        empresarial, industrial y comercial de la zona sur de Honduras.
                    </p>
                </div>
                <div class="ccisur-footer-column">
                    <h3>Enlaces rápidos</h3>
                    <ul>
                        <li><a href="https://www.ccisur.org/" target="_blank" rel="noopener">Inicio</a></li>
                        <li><a href="https://www.ccisur.org/Centro_Asociado/" target="_blank" rel="noopener">Centro
                                Asociado del Sur</a></li>
                        <li><a href="https://www.ccisur.org/formalizacion/" target="_blank" rel="noopener">Afiliación</a>
                        </li>
                        <li><a href="https://www.ccisur.org/Intermediacion/" target="_blank" rel="noopener">Intermediación
                                Laboral</a></li>
                        <li><a href="https://www.ccisur.org/Sobre_Nosotros/" target="_blank" rel="noopener">Contáctanos</a>
                        </li>
                    </ul>
                </div>
                <div class="ccisur-footer-column">
                    <h3>Contáctanos</h3>
                    <p>
                        <strong>Dirección:</strong> Ciudad Balcanes, Parque Industrial Honduras Pacific, Choluteca,
                        Honduras.
                    </p>
                    <p>
                        <strong>WhatsApp:</strong> <a href="https://wa.me/50433150844" target="_blank"
                            rel="noopener" style="color:inherit;">+504 3315-0844</a> / PBX: 2782-2929
                    </p>
                    <p>
                        <strong>Correo:</strong> info@ccisur.org
                    </p>
                    <p>
                        <strong>Horario:</strong> Lunes a viernes, 8:00 a.m. – 4:00 p.m.
                    </p>
                </div>
                <div class="ccisur-footer-column">
                    <h3>Redes sociales</h3>
                    <div class="ccisur-social-icons">
                        <a href="https://www.facebook.com/CCICholuteca" target="_blank" rel="noopener"
                            aria-label="Facebook" title="Facebook">
                            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path
                                    d="M13.5 22v-8h2.7l.4-3h-3.1V8.1c0-.9.3-1.5 1.6-1.5H16.8V3.8c-.5-.1-1.6-.2-2.8-.2-2.8 0-4.7 1.7-4.7 4.8V11H7v3h2.3v8h4.2z" />
                            </svg>
                        </a>
                        <a href="https://x.com/CCIS_Choluteca" target="_blank" rel="noopener" aria-label="X"
                            title="X">
                            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path
                                    d="M17.3 3H20l-5.8 6.7L21 21h-6.2l-4.8-6.2L4.6 21H2l6.3-7.2L2 3h6.3l4.4 5.7L17.3 3zm-1.1 16h1.5L7.5 4.9H5.9L16.2 19z" />
                            </svg>
                        </a>
                        <a href="https://hn.linkedin.com/company/camara-de-comercio-e-industrias-del-sur"
                            target="_blank" rel="noopener" aria-label="LinkedIn" title="LinkedIn">
                            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path
                                    d="M6.5 8.5H3.8V21h2.7V8.5zM5.1 3C4.1 3 3.3 3.8 3.3 4.8s.8 1.8 1.8 1.8 1.8-.8 1.8-1.8S6.1 3 5.1 3zM20.2 21h-2.7v-6.4c0-1.5 0-3.5-2.1-3.5s-2.4 1.6-2.4 3.4V21h-2.7V8.5h2.6v1.7h.1c.4-.9 1.6-1.9 3.3-1.9 3.6 0 4.2 2.4 4.2 5.4V21z" />
                            </svg>
                        </a>
                        <a href="https://www.instagram.com/ccisur.hn/" target="_blank" rel="noopener"
                            aria-label="Instagram" title="Instagram">
                            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path
                                    d="M7 3.5h10A3.5 3.5 0 0 1 20.5 7v10A3.5 3.5 0 0 1 17 20.5H7A3.5 3.5 0 0 1 3.5 17V7A3.5 3.5 0 0 1 7 3.5zm0 1.8A1.7 1.7 0 0 0 5.3 7v10A1.7 1.7 0 0 0 7 18.7h10a1.7 1.7 0 0 0 1.7-1.7V7A1.7 1.7 0 0 0 17 5.3H7zm5 2.4A4.1 4.1 0 1 1 8 15a4.1 4.1 0 0 1 4-7.3zm0 1.8A2.3 2.3 0 1 0 14.3 12 2.3 2.3 0 0 0 12 9.5zm4.8-.9a1 1 0 1 1-1 1 1 1 0 0 1 1-1z" />
                            </svg>
                        </a>
                        <a href="https://wa.me/50433150844" target="_blank" rel="noopener" aria-label="WhatsApp"
                            title="WhatsApp">
                            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path
                                    d="M12 3.2A8.8 8.8 0 0 0 4.5 16.5L3.3 21l4.6-1.2A8.8 8.8 0 1 0 12 3.2zm0 1.8a7 7 0 0 1 6.1 10.4l-.2.3a7 7 0 0 1-8.4 2.8l-.3-.1-2.7.7.7-2.6-.2-.3A7 7 0 0 1 12 5zm-3.3 3.4c-.2.1-.4.2-.6.5-.2.3-.7.8-.7 1.9s.8 2.3.9 2.4c.1.1 1.6 2.6 4 3.6 2 .8 2.4.6 2.8.6.4-.1 1.4-.6 1.6-1.2.2-.6.2-1 .1-1.2-.1-.1-.3-.2-.6-.4l-1.7-.8c-.2-.1-.4-.1-.6.1l-.8 1c-.1.1-.3.1-.5 0-.2-.1-.9-.3-1.8-1.1-.7-.6-1.2-1.4-1.4-1.6-.1-.2 0-.4.1-.5l.4-.5c.1-.1.1-.3.2-.4 0-.1 0-.3 0-.4l-.8-2c-.1-.2-.3-.4-.5-.4h-.7z" />
                            </svg>
                        </a>
                        <a href="https://www.tiktok.com/@ccisur.hn" target="_blank" rel="noopener"
                            aria-label="TikTok" title="TikTok">
                            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path
                                    d="M16.6 3c.4 2.4 1.7 3.8 3.9 4v3.1c-1.6.1-3-.4-4.4-1.4v6.1c0 3.2-2.6 5.9-5.9 5.9S4.3 18 4.3 14.8s2.6-5.9 5.9-5.9c.3 0 .7 0 1 .1v3.3c-.3-.1-.6-.2-1-.2-1.5 0-2.7 1.2-2.7 2.7s1.2 2.7 2.7 2.7 2.8-1.2 2.8-2.7V3h3.6z" />
                            </svg>
                        </a>
                        <a href="https://www.youtube.com/@CCISur" target="_blank" rel="noopener"
                            aria-label="YouTube" title="YouTube">
                            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path
                                    d="M21.8 8.2s-.2-1.4-.8-2c-.8-.9-1.7-.9-2.1-1-3.1-.2-7-.2-7-.2s-3.9 0-7 .2c-.4 0-1.3.1-2.1 1-.6.6-.8 2-.8 2S2 9.7 2 11.1v1.8c0 1.4.2 2.9.2 2.9s.2 1.4.8 2c.8.9 1.8.9 2.3 1 1.7.2 6.7.2 6.7.2s3.9 0 7-.2c.4 0 1.3-.1 2.1-1 .6-.6.8-2 .8-2s.2-1.5.2-2.9v-1.8c0-1.4-.2-2.9-.2-2.9zM10 15.1V8.9l5.8 3.1L10 15.1z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="ccisur-footer-bottom">
                <p>&copy; {{ date('Y') }} Cámara de Comercio e Industrias del Sur. Todos los derechos reservados.</p>
                <p>CCISUR · Choluteca, Honduras</p>
            </div>
        </div>
    </footer>

    <!-- Help Modal -->
    <div class="modal fade" id="helpModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-question-circle me-2"></i> Centro de Ayuda</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="help-item">
                        <h6 class="help-item-title"><i class="fas fa-search me-2"></i>¿Cómo buscar tu certificado?</h6>
                        <p>Ingresa tu número de identidad completo (13 dígitos, sin guiones ni espacios) en el campo
                            correspondiente y haz clic en el botón <strong>"Buscar Certificados"</strong>.</p>
                    </div>

                    <div class="help-item">
                        <h6 class="help-item-title"><i class="fas fa-exclamation-circle me-2"></i>¿No encuentras tu
                            certificado?</h6>
                        <p>Verifica que hayas ingresado correctamente tu número de identidad. Si el problema persiste,
                            puede que tu certificado aún no esté disponible en el sistema o haya un error en los datos.
                        </p>
                    </div>

                    <div class="help-item">
                        <h6 class="help-item-title"><i class="fas fa-headset me-2"></i>Soporte técnico</h6>
                        <p>Para asistencia personalizada, contacta a nuestro equipo de soporte:</p>
                        <p>
                            <i class="fab fa-whatsapp me-2"></i>
                            <a href="https://wa.me/50433150844" target="_blank" rel="noopener" class="contact-link">+504
                                3315-0844</a>
                        </p>
                        <p>
                            <i class="fas fa-envelope me-2"></i>
                            <a href="mailto:it@ccisur.org" class="contact-link">it@ccisur.org</a>
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('certSearchForm');
            const btn = document.getElementById('certBtn');
            const spinner = btn.querySelector('.spinner-border');

            // Form validation and submission
            form.addEventListener('submit', function(e) {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                } else {
                    btn.disabled = true;
                    btn.classList.add('btn-loading');
                    spinner.classList.remove('d-none');
                }

                form.classList.add('was-validated');
            }, false);

            // Input formatting for identity number
            const identidadInput = document.getElementById('identidad');
            identidadInput.addEventListener('input', function(e) {
                // Remove any non-digit characters
                this.value = this.value.replace(/\D/g, '');

                // Validate length
                if (this.value.length > 13) {
                    this.value = this.value.slice(0, 13);
                }
            });

            // Add animation class to elements
            const animateElements = document.querySelectorAll('.animate-fade-in');
            animateElements.forEach((el, index) => {
                el.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
</body>

</html>
