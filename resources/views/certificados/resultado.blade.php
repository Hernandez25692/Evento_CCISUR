<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mis Certificados | CCISUR</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a0ca3;
            --primary-light: #e6f0ff;
            --text: #2b2d42;
            --text-light: #858796;
            --white: #ffffff;
            --gray: #f8f9fa;
            --border: #e9ecef;
            --success: #4bb543;
            --border-radius: 12px;
            --box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            --transition: all 0.2s ease;
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

        /* Header */
        .app-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: var(--white);
            padding: 1.25rem 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .header-inner {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .back-link {
            color: #fff;
            font-size: 1.3rem;
            opacity: .85;
            transition: var(--transition);
            flex-shrink: 0;
        }

        .back-link:hover {
            opacity: 1;
        }

        .logo-icon {
            width: 44px;
            height: 44px;
            background-color: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .logo-text {
            font-weight: 700;
            font-size: 1.35rem;
            letter-spacing: .5px;
        }

        .logo-text span:first-child {
            color: #4cc9f0;
        }

        .logo-subtext {
            font-size: .85rem;
            opacity: .85;
            font-weight: 300;
        }

        /* Main */
        .main-content {
            flex: 1;
            padding: 2rem 0 3rem;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 1.25rem;
        }

        /* Participant summary bar */
        .participant-bar {
            display: flex;
            align-items: center;
            gap: 1.1rem;
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 1.25rem 1.5rem;
            margin-bottom: 1.25rem;
        }

        .participant-avatar {
            flex-shrink: 0;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .participant-details {
            min-width: 0;
            flex: 1;
        }

        .participant-name {
            font-weight: 600;
            font-size: 1.15rem;
            color: var(--text);
        }

        .participant-meta {
            display: flex;
            flex-wrap: wrap;
            gap: .35rem 1.25rem;
            font-size: .88rem;
            color: var(--text-light);
            margin-top: .15rem;
        }

        .participant-meta i {
            color: var(--primary);
            margin-right: .35em;
        }

        .participant-count {
            flex-shrink: 0;
            background: var(--primary-light);
            color: var(--primary-dark);
            font-weight: 600;
            font-size: .85rem;
            padding: .5em .9em;
            border-radius: 999px;
            white-space: nowrap;
        }

        /* Toolbar: search */
        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        .toolbar-title {
            font-weight: 600;
            font-size: 1.05rem;
            display: flex;
            align-items: center;
            gap: .5em;
        }

        .toolbar-title i {
            color: var(--primary);
        }

        .search-box {
            position: relative;
            flex: 1;
            max-width: 320px;
        }

        .search-box i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: .9rem;
        }

        .search-box input {
            width: 100%;
            border: 1px solid var(--border);
            border-radius: var(--border-radius);
            padding: .6rem 1rem .6rem 2.4rem;
            font-family: inherit;
            font-size: .92rem;
            background: var(--white);
            transition: var(--transition);
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.12);
        }

        /* Table card */
        .table-card {
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
        }

        table.diplomas-table {
            width: 100%;
            border-collapse: collapse;
        }

        .diplomas-table thead th {
            text-align: left;
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: var(--text-light);
            font-weight: 600;
            background: var(--gray);
            padding: .9rem 1.25rem;
            border-bottom: 1px solid var(--border);
        }

        .diplomas-table tbody tr {
            transition: var(--transition);
        }

        .diplomas-table tbody tr:not(:last-child) {
            border-bottom: 1px solid var(--border);
        }

        .diplomas-table tbody tr:hover {
            background: var(--primary-light);
        }

        .diplomas-table td {
            padding: 1rem 1.25rem;
            font-size: .92rem;
            vertical-align: middle;
        }

        .curso-nombre {
            font-weight: 600;
            color: var(--text);
        }

        .curso-nombre i {
            color: var(--primary);
            margin-right: .5em;
        }

        .col-accion {
            text-align: right;
        }

        .download-btn {
            display: inline-flex;
            align-items: center;
            gap: .5em;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #fff;
            border: none;
            border-radius: .6em;
            padding: .55em 1.1em;
            font-weight: 500;
            font-size: .88rem;
            text-decoration: none;
            transition: var(--transition);
            white-space: nowrap;
        }

        .download-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.25);
        }

        .badge-no {
            display: inline-flex;
            align-items: center;
            gap: .5em;
            background: var(--gray);
            color: var(--text-light);
            border-radius: .6em;
            padding: .55em 1.1em;
            font-weight: 500;
            font-size: .85rem;
            border: 1px solid var(--border);
            white-space: nowrap;
        }

        .fila-oculta-filtro {
            display: none !important;
        }

        /* Empty states */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: .75rem;
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 3rem 1.5rem;
            color: var(--text-light);
        }

        .empty-state i {
            font-size: 2.6rem;
            color: var(--primary-light);
        }

        .empty-state h2 {
            font-size: 1.15rem;
            color: var(--text);
            font-weight: 600;
        }

        .empty-state p {
            font-size: .92rem;
            max-width: 360px;
        }

        /* Mobile: table -> stacked cards */
        @media (max-width: 700px) {
            .diplomas-table thead {
                display: none;
            }

            .diplomas-table,
            .diplomas-table tbody,
            .diplomas-table tr,
            .diplomas-table td {
                display: block;
                width: 100%;
            }

            .diplomas-table tbody tr {
                padding: 1rem 1.25rem;
            }

            .diplomas-table td {
                padding: .3rem 0;
                text-align: left !important;
            }

            .diplomas-table td[data-label]::before {
                content: attr(data-label);
                display: block;
                font-size: .72rem;
                text-transform: uppercase;
                letter-spacing: .04em;
                color: var(--text-light);
                font-weight: 600;
                margin-bottom: .1rem;
            }

            .col-accion {
                margin-top: .5rem;
            }

            .search-box {
                flex-basis: 100%;
                max-width: none;
            }

            .download-btn,
            .badge-no {
                width: 100%;
                justify-content: center;
            }

            .participant-bar {
                flex-wrap: wrap;
            }

            .participant-count {
                flex-basis: 100%;
                margin-left: 0;
                margin-top: .5rem;
            }
        }

        /* Footer */
        .app-footer {
            background-color: var(--white);
            padding: 1.5rem 0;
            text-align: center;
            font-size: .88rem;
            color: var(--text-light);
            box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body class="app-container">
    <header class="app-header">
        <div class="container header-inner">
            <a href="{{ route('certificados.buscar') }}" class="back-link" title="Buscar otro certificado">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="logo-icon">
                <i class="fas fa-certificate"></i>
            </div>
            <div>
                <div class="logo-text"><span>FORMACIONES</span> CCISUR</div>
                <div class="logo-subtext">Mis Certificados</div>
            </div>
        </div>
    </header>

    <main class="main-content">
        <div class="container">
            @if (!$participante)
                <div class="empty-state">
                    <i class="fas fa-user-slash"></i>
                    <h2>No encontramos resultados</h2>
                    <p>No existe ningún participante registrado con esa identidad. Verifica el número e inténtalo de
                        nuevo.</p>
                    <a href="{{ route('certificados.buscar') }}" class="download-btn" style="margin-top:.5rem;">
                        <i class="fas fa-search"></i> Buscar de nuevo
                    </a>
                </div>
            @else
                <div class="participant-bar">
                    <div class="participant-avatar">
                        {{ strtoupper(mb_substr($participante->nombre_completo, 0, 1)) }}
                    </div>
                    <div class="participant-details">
                        <div class="participant-name">{{ $participante->nombre_completo }}</div>
                        <div class="participant-meta">
                            <span><i
                                    class="fas fa-id-card"></i>{{ $participante->identidad ? substr($participante->identidad, 0, 3) . str_repeat('*', max(strlen($participante->identidad) - 6, 0)) . substr($participante->identidad, -3) : 'No especificado' }}</span>
                            <span><i
                                    class="fas fa-envelope"></i>{{ $participante->correo ? preg_replace('/(^.).*(@.*$)/', '$1***$2', $participante->correo) : 'No especificado' }}</span>
                        </div>
                    </div>
                    <div class="participant-count">
                        {{ $participante->capacitaciones->count() }}
                        {{ $participante->capacitaciones->count() === 1 ? 'capacitación' : 'capacitaciones' }}
                    </div>
                </div>

                @if ($participante->capacitaciones->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-info-circle"></i>
                        <h2>Sin diplomas registrados</h2>
                        <p>Este participante aún no tiene capacitaciones ni diplomas disponibles.</p>
                    </div>
                @else
                    <div class="toolbar">
                        <div class="toolbar-title">
                            <i class="fas fa-graduation-cap"></i> Diplomas disponibles
                        </div>
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" id="filtroCurso" placeholder="Buscar curso...">
                        </div>
                    </div>

                    <div class="table-card">
                        <table class="diplomas-table">
                            <thead>
                                <tr>
                                    <th>Curso</th>
                                    <th>Fecha</th>
                                    <th>Tipo de Formación</th>
                                    <th>Duración (H)</th>
                                    <th class="col-accion">Acción</th>
                                </tr>
                            </thead>
                            <tbody id="cuerpoTablaDiplomas">
                                @foreach ($participante->capacitaciones as $capacitacion)
                                    @php
                                        $habilitado = $capacitacion->pivot->habilitado_diploma ?? false;
                                    @endphp
                                    <tr data-nombre="{{ mb_strtolower($capacitacion->nombre) }}">
                                        <td data-label="Curso">
                                            <span class="curso-nombre">
                                                <i class="fas fa-book-open"></i>{{ $capacitacion->nombre }}
                                            </span>
                                        </td>
                                        <td data-label="Fecha">
                                            {{ \Carbon\Carbon::parse($capacitacion->fecha_inicio)->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}
                                        </td>
                                        <td data-label="Modalidad">
                                            {{ ucfirst($capacitacion->tipo_formacion ?? 'No especificada') }}
                                        </td>
                                        <td data-label="Duración">
                                            {{ $capacitacion->duracion ?: 'No especificada' }}
                                        </td>
                                        <td data-label="Diploma" class="col-accion">
                                            @if ($habilitado)
                                                <a href="{{ route('certificados.descargar', [$capacitacion->id, $participante->identidad]) }}"
                                                    class="download-btn" target="_blank">
                                                    <i class="fas fa-download"></i> Descargar
                                                </a>
                                            @else
                                                <span class="badge-no">
                                                    <i class="fas fa-lock"></i> No habilitado
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="empty-state fila-oculta-filtro" id="sinResultadosFiltro" style="margin-top:1rem;">
                        <i class="fas fa-search"></i>
                        <h2>Sin coincidencias</h2>
                        <p>Ningún curso coincide con tu búsqueda.</p>
                    </div>
                @endif
            @endif
        </div>
    </main>

    <footer class="app-footer">
        <div class="container">
            &copy; {{ date('Y') }} <strong>Formaciones CCISUR</strong> - Todos los derechos reservados
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('filtroCurso');
            if (!input) return;

            const filas = Array.from(document.querySelectorAll('#cuerpoTablaDiplomas tr'));
            const sinResultados = document.getElementById('sinResultadosFiltro');

            input.addEventListener('input', function() {
                const termino = input.value.trim().toLowerCase();
                let visibles = 0;

                filas.forEach(function(fila) {
                    const coincide = fila.dataset.nombre.includes(termino);
                    fila.classList.toggle('fila-oculta-filtro', !coincide);
                    if (coincide) visibles++;
                });

                sinResultados.classList.toggle('fila-oculta-filtro', visibles !== 0);
            });
        });
    </script>
</body>

</html>
