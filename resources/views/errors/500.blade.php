<!-- resources/views/errors/500.blade.php -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Error 500 - Error del servidor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fff3cd;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
        }

        .error-code {
            font-size: 8rem;
            font-weight: bold;
            color: #ffc107;
        }

        .error-message {
            font-size: 1.5rem;
            color: #856404;
        }
    </style>
</head>

<body>
    <div class="text-center">
        <div class="error-code">500</div>
        <div class="error-message">Error interno del servidor</div>
        <p>Algo salió mal en el servidor. Estamos trabajando para solucionarlo.</p>
        <a href="{{ url('/') }}" class="btn btn-warning mt-3">Volver al inicio</a>
    </div>
</body>

</html>
