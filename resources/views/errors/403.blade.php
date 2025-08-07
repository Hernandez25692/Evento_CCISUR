<!-- resources/views/errors/403.blade.php -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Error 403 - Acceso prohibido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8d7da;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
        }

        .error-code {
            font-size: 8rem;
            font-weight: bold;
            color: #dc3545;
        }

        .error-message {
            font-size: 1.5rem;
            color: #721c24;
        }
    </style>
</head>

<body>
    <div class="text-center">
        <div class="error-code">403</div>
        <div class="error-message">Acceso prohibido</div>
        <p>No tienes permisos para acceder a esta sección.</p>
        <a href="{{ url('/') }}" class="btn btn-danger mt-3">Volver al inicio</a>
    </div>
</body>

</html>
