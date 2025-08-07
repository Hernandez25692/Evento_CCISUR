<!-- resources/views/errors/419.blade.php -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Error 419 - Sesión expirada</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #e2e3e5;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
        }

        .error-code {
            font-size: 8rem;
            font-weight: bold;
            color: #6c757d;
        }

        .error-message {
            font-size: 1.5rem;
            color: #343a40;
        }
    </style>
</head>

<body>
    <div class="text-center">
        <div class="error-code">419</div>
        <div class="error-message">Sesión expirada</div>
        <p>Tu sesión ha expirado. Por favor, vuelve a cargar la página o inicia sesión nuevamente.</p>
        <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">Volver</a>
    </div>
</body>

</html>
