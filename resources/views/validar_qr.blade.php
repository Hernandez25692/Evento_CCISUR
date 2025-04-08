<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diploma Verificado - CCISUR</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #1d3557;
            --secondary: #457b9d;
            --accent: #00ffae;
            --light: #f1faee;
            --dark: #0a192f;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            --border-radius: 16px;
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
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
            position: relative;
            overflow: hidden;
            transition: var(--transition);
        }

        .verification-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(0, 255, 174, 0.1) 0%, transparent 70%);
            animation: rotate 15s linear infinite;
            z-index: -1;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .logo-container {
            margin-bottom: 2rem;
            position: relative;
        }

        .logo {
            width: 120px;
            height: auto;
            filter: drop-shadow(0 2px 5px rgba(0, 0, 0, 0.2));
            transition: var(--transition);
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
            box-shadow: 0 5px 15px rgba(0, 255, 174, 0.3);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            color: var(--accent);
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .verification-text {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .details {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 2rem;
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
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .footer {
            margin-top: 2rem;
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .seal {
            margin-top: 2rem;
            width: 80px;
            opacity: 0.8;
        }

        @media (max-width: 768px) {
            .verification-card {
                padding: 2rem;
            }

            h1 {
                font-size: 2rem;
            }

            .verification-text {
                font-size: 1.1rem;
            }
        }

        @media (max-width: 480px) {
            .verification-card {
                padding: 1.5rem;
            }

            h1 {
                font-size: 1.8rem;
            }

            .logo {
                width: 100px;
            }

            .verification-badge {
                width: 50px;
                height: 50px;
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="verification-card">
        <div class="logo-container">
            <img src="{{ asset('storage/logo/Logo-CCISUR.png') }}"  class="logo" alt="Logo">
        </div>
        
        <div class="verification-badge">
            <i class="fas fa-check" style="font-size: 2rem; color: var(--primary);"></i>
        </div>
        
        <h1>DIPLOMA VERIFICADO</h1>
        
        <p class="verification-text">
            Este certificado fue emitido oficialmente por la Cámara de Comercio e Industria del Sur (CCISUR) 
            y ha sido validado en nuestro sistema de registros.
        </p>
        
        <div class="details">
            <div class="detail-item">
                <div class="detail-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <strong>Autenticidad garantizada</strong><br>
                    Este documento cuenta con medidas de seguridad digital para prevenir falsificaciones.
                </div>
            </div>
            <div class="detail-item">
                <div class="detail-icon">
                    <i class="fas fa-database"></i>
                </div>
                <div>
                    <strong>Registro permanente</strong><br>
                    La información de este diploma se encuentra almacenada en nuestros archivos oficiales.
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p>Para consultas adicionales, contacte al departamento de registros de CCISUR.</p>
        </div>
        
        <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDAgMTAwIj48Y2lyY2xlIGN4PSI1MCIgY3k9IjUwIiByPSI0MCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjMDBmZmFlIiBzdHJva2Utd2lkdGg9IjIiLz48cGF0aCBkPSJNMzUgNTAgTDUwIDY1IEw3NSA0MCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjMDBmZmFlIiBzdHJva2Utd2lkdGg9IjQiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIvPjwvc3ZnPg==" 
             alt="Sello de verificación" class="seal">
    </div>

    <script>
        // Efecto de aparición gradual
        document.addEventListener('DOMContentLoaded', function() {
            const card = document.querySelector('.verification-card');
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
            
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>
</html>