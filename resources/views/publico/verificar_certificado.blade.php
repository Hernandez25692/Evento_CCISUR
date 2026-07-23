<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $valido ? 'Certificado Verificado' : 'Código no válido' }} - CCISUR</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #1d3557;
            --secondary: #457b9d;
            --accent: {{ $valido ? '#00ffae' : '#ff6b6b' }};
            --light: #f1faee;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            --border-radius: 16px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: var(--light);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            line-height: 1.6;
        }

        .verification-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            padding: 3rem;
            max-width: 600px;
            width: 100%;
            text-align: center;
            box-shadow: var(--shadow);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logo-container {
            margin-bottom: 2rem;
        }

        .logo {
            width: 120px;
            height: auto;
            filter: drop-shadow(0 2px 5px rgba(0, 0, 0, 0.2));
        }

        .verification-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: var(--accent);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-bottom: 1.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.25);
        }

        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            margin-bottom: 1.5rem;
            color: var(--accent);
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .verification-text {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .details {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 1rem;
            text-align: left;
        }

        .detail-item {
            display: flex;
            margin-bottom: 1rem;
        }

        .detail-item:last-child {
            margin-bottom: 0;
        }

        .detail-icon {
            color: var(--accent);
            margin-right: 1rem;
            font-size: 1.1rem;
            flex-shrink: 0;
            width: 20px;
            text-align: center;
        }

        .detail-item strong {
            display: block;
            font-size: 0.85rem;
            opacity: 0.8;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .footer {
            margin-top: 2rem;
            font-size: 0.85rem;
            opacity: 0.8;
        }

        @media (max-width: 480px) {
            .verification-card {
                padding: 1.5rem;
            }

            h1 {
                font-size: 1.6rem;
            }
        }
    </style>
</head>
<body>
    <div class="verification-card">
        <div class="logo-container">
            <img src="{{ asset('storage/logo/Logo-CCISUR.png') }}" class="logo" alt="Logo CCISUR">
        </div>

        <div class="verification-badge">
            <i class="fas {{ $valido ? 'fa-check' : 'fa-exclamation' }}" style="font-size: 1.6rem; color: var(--primary);"></i>
        </div>

        @if ($valido)
            <h1>CERTIFICADO AUTÉNTICO</h1>
            <p class="verification-text">
                Este certificado fue emitido oficialmente por la <strong>Cámara de Comercio e Industrias del Sur
                (CCISUR)</strong> y ha sido validado en nuestro sistema de registros.
            </p>

            <div class="details">
                <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-user"></i></div>
                    <div>
                        <strong>Participante</strong>
                        {{ $participante->nombre_completo }}
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-graduation-cap"></i></div>
                    <div>
                        <strong>Capacitación</strong>
                        {{ $capacitacion->nombre }}
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-layer-group"></i></div>
                    <div>
                        <strong>Modalidad y duración</strong>
                        {{ $modalidadDuracion }}
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-calendar-alt"></i></div>
                    <div>
                        <strong>Lugar y fecha de emisión</strong>
                        {{ $lugarFecha }}
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                    <div>
                        <strong>Facilitador</strong>
                        {{ $impartidoPor }}
                    </div>
                </div>
            </div>
        @else
            <h1>CÓDIGO NO VÁLIDO</h1>
            <p class="verification-text">
                No se encontró ningún certificado asociado a este código, o el certificado ya no está habilitado.
                Si crees que esto es un error, comunícate con la Cámara de Comercio e Industrias del Sur (CCISUR)
                para confirmar la validez del documento.
            </p>
        @endif

        <div class="footer">
            <p>Para consultas adicionales, contacte al departamento de registros de CCISUR.</p>
        </div>
    </div>
</body>
</html>
