<!-- resources/views/errors/404.blade.php -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Error 404 - Página no encontrada</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            font-family: 'Poppins', sans-serif;
        }

        .error-container {
            text-align: center;
        }

        .error-code {
            font-size: 6rem;
            font-weight: bold;
            color: #dc3545;
        }

        .error-message {
            font-size: 1.5rem;
        }
    </style>
</head>

<body>
    <div class="error-container">
        <div class="error-code">404</div>
        <div class="error-message">Lo sentimos, la página que buscas no existe.</div>
        <a href="{{ url('/') }}" class="btn btn-primary mt-4">Ir al Inicio</a>
    </div>
</body>

</html>
