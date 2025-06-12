@extends('layouts.app')

@section('content')
<div class="certificados-publicos">
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <div class="hero-icon">
                <i class="fas fa-award"></i>
            </div>
            <h1 class="hero-title">¡Tus logros, tu orgullo!</h1>
            <p class="hero-desc">
                Consulta y descarga tus diplomas de capacitación.<br>
                <span class="hero-highlight">¡Sigue creciendo y celebrando tus éxitos!</span>
            </p>
        </div>
    </section>

    <main class="main-content">
        @if(!$participante)
            <div class="empty-state">
                <i class="fas fa-user-slash empty-icon"></i>
                <div>
                    <h2>No encontramos resultados</h2>
                    <p>No existe ningún participante con esa identidad.</p>
                </div>
            </div>
        @else
            <!-- Participant Card -->
            <section class="participant-card">
                <div class="avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="participant-info">
                    <h2 class="participant-name">{{ $participante->nombre_completo }}</h2>
                    <div class="info-list">
                        <div class="info-item">
                            <i class="fas fa-envelope"></i>
                            <span>{{ $participante->correo ?: 'No especificado' }}</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-id-card"></i>
                            <span>{{ $participante->identidad }}</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-graduation-cap"></i>
                            <span>{{ $participante->capacitaciones->count() }} capacitación(es)</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Diplomas List -->
            <section class="diplomas-section">
                <h3 class="diplomas-title">
                    <i class="fas fa-certificate"></i> Diplomas Disponibles
                </h3>
                @if($participante->capacitaciones->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-info-circle empty-icon"></i>
                        <div>
                            <h2>Sin diplomas registrados</h2>
                            <p>Este participante aún no tiene diplomas disponibles.</p>
                        </div>
                    </div>
                @else
                    <div class="diplomas-grid">
                        @foreach($participante->capacitaciones as $capacitacion)
                            @php
                                $habilitado = $capacitacion->pivot->habilitado_diploma ?? false;
                            @endphp
                            <div class="diploma-card scroll-reveal">
                                <div class="diploma-icon">
                                    <i class="fas fa-book-open"></i>
                                </div>
                                <div class="diploma-info">
                                    <h4 class="diploma-title">{{ $capacitacion->nombre }}</h4>
                                    <div class="diploma-meta">
                                        <div>
                                            <i class="fas fa-calendar-day"></i>
                                            <span>{{ \Carbon\Carbon::parse($capacitacion->fecha_inicio)->isoFormat('D [de] MMMM [de] YYYY') }}</span>
                                        </div>
                                        <div>
                                            <i class="fas fa-laptop"></i>
                                            <span>
                                                {{ ucfirst($capacitacion->tipo_formacion ?? 'No especificada') }}
                                            </span>
                                        </div>
                                        <div>
                                            <i class="fas fa-clock"></i>
                                            <span>{{ $capacitacion->forma ?: 'No especificada' }}</span>
                                        </div>
                                        <div>
                                            <i class="fas fa-clock"></i>
                                            <span>{{ $capacitacion->duracion ?: 'Duración no especificada' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="diploma-actions">
                                    @if($habilitado)
                                        <a href="{{ route('certificados.descargar', [$capacitacion->id, $participante->id]) }}"
                                           class="download-btn" target="_blank">
                                            <i class="fas fa-download"></i> Descargar Diploma
                                        </a>
                                    @else
                                        <span class="badge-no">
                                            <i class="fas fa-lock"></i> No habilitado
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>
        @endif
    </main>
</div>

<!-- FontAwesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

<style>
:root {
    --primary: #4361ee;
    --secondary: #4cc9f0;
    --bg: #f1f3f5;
    --white: #fff;
    --text: #2b2d42;
    --gray: #adb5bd;
    --shadow: 0 4px 24px 0 rgba(67,97,238,0.08);
    --radius: 1.25rem;
    --transition: all .3s cubic-bezier(.4,0,.2,1);
}

/* Layout */
.certificados-publicos {
    min-height: 100vh;
    background: var(--bg);
    font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    color: var(--text);
    padding-bottom: 2rem;
}

/* Hero */
.hero {
    background: linear-gradient(120deg, var(--primary) 60%, var(--secondary) 100%);
    color: var(--white);
    padding: clamp(2rem, 8vw, 4.5rem) 1rem 2.5rem 1rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.hero::before {
    content: '';
    position: absolute;
    left: -10vw; top: -10vw;
    width: 40vw; height: 40vw;
    background: radial-gradient(circle, #fff 0%, #4cc9f0 80%, transparent 100%);
    opacity: 0.10;
    z-index: 0;
}
.hero-content {
    position: relative;
    z-index: 1;
    max-width: 600px;
    margin: 0 auto;
}
.hero-icon {
    font-size: clamp(3rem, 8vw, 5rem);
    margin-bottom: 1.5rem;
    color: #fff;
    filter: drop-shadow(0 4px 16px rgba(67,97,238,0.18));
}
.hero-title {
    font-size: clamp(2rem, 6vw, 3rem);
    font-weight: 800;
    margin-bottom: 0.5rem;
    letter-spacing: -1px;
}
.hero-desc {
    font-size: clamp(1.1rem, 3vw, 1.35rem);
    font-weight: 400;
    opacity: .95;
    margin-bottom: 0;
}
.hero-highlight {
    color: var(--secondary);
    font-weight: 600;
}

/* Main Content */
.main-content {
    max-width: 900px;
    margin: 0 auto;
    padding: clamp(1rem, 4vw, 2.5rem) 1rem 0 1rem;
}

/* Participant Card */
.participant-card {
    display: flex;
    align-items: center;
    background: var(--white);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: clamp(1.25rem, 4vw, 2.5rem);
    margin-bottom: 2.5rem;
    gap: 2rem;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}
.participant-card:hover {
    box-shadow: 0 8px 32px 0 rgba(67,97,238,0.13);
    transform: translateY(-4px) scale(1.01);
}
.avatar {
    flex-shrink: 0;
    width: clamp(70px, 12vw, 100px);
    height: clamp(70px, 12vw, 100px);
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary) 60%, var(--secondary) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: clamp(2.5rem, 6vw, 3.5rem);
    box-shadow: 0 2px 12px 0 rgba(67,97,238,0.10);
}
.participant-info {
    flex: 1 1 0;
    min-width: 0;
}
.participant-name {
    font-size: clamp(1.3rem, 4vw, 2rem);
    font-weight: 700;
    margin-bottom: 1.1rem;
    color: var(--primary);
    letter-spacing: -0.5px;
}
.info-list {
    display: flex;
    flex-wrap: wrap;
    gap: 1.2rem 2.5rem;
}
.info-item {
    display: flex;
    align-items: center;
    font-size: clamp(1rem, 2.5vw, 1.1rem);
    color: var(--text);
    gap: 0.7rem;
}
.info-item i {
    color: var(--secondary);
    font-size: 1.1em;
}

/* Diplomas Section */
.diplomas-section {
    margin-top: 1.5rem;
}
.diplomas-title {
    font-size: clamp(1.2rem, 3vw, 1.5rem);
    font-weight: 700;
    margin-bottom: 1.5rem;
    color: var(--text);
    display: flex;
    align-items: center;
    gap: 0.7rem;
}
.diplomas-title i {
    color: var(--primary);
    font-size: 1.2em;
}

/* Diplomas Grid */
.diplomas-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
}
@media (min-width: 600px) {
    .diplomas-grid {
        grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
    }
}

/* Diploma Card */
.diploma-card {
    display: flex;
    align-items: center;
    background: var(--white);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: clamp(1rem, 3vw, 1.5rem);
    gap: 1.5rem;
    transition: var(--transition);
    opacity: 0;
    transform: translateY(30px);
}
.diploma-card:hover {
    box-shadow: 0 8px 32px 0 rgba(67,97,238,0.13);
    transform: translateY(-4px) scale(1.01);
}
.diploma-icon {
    flex-shrink: 0;
    width: 60px;
    height: 60px;
    border-radius: 1rem;
    background: linear-gradient(135deg, var(--primary) 60%, var(--secondary) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 2rem;
    box-shadow: 0 2px 12px 0 rgba(67,97,238,0.10);
}
.diploma-info {
    flex: 1 1 0;
    min-width: 0;
}
.diploma-title {
    font-size: clamp(1.1rem, 2.5vw, 1.25rem);
    font-weight: 600;
    margin-bottom: 0.7rem;
    color: var(--primary);
    letter-spacing: -0.5px;
}
.diploma-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.8rem 2.2rem;
    font-size: clamp(0.98rem, 2vw, 1.05rem);
    color: var(--gray);
}
.diploma-meta i {
    color: var(--secondary);
    margin-right: 0.4em;
}
.diploma-actions {
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.5rem;
    min-width: 120px;
}
.download-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5em;
    background: linear-gradient(90deg, var(--primary) 60%, var(--secondary) 100%);
    color: #fff;
    border: none;
    border-radius: 0.7em;
    padding: 0.65em 1.2em;
    font-weight: 600;
    font-size: 1em;
    text-decoration: none;
    box-shadow: 0 2px 8px 0 rgba(67,97,238,0.10);
    transition: var(--transition);
    cursor: pointer;
}
.download-btn:hover {
    background: linear-gradient(90deg, var(--secondary) 60%, var(--primary) 100%);
    transform: translateY(-2px) scale(1.03);
    box-shadow: 0 4px 16px 0 rgba(67,97,238,0.13);
}
.badge-no {
    display: inline-flex;
    align-items: center;
    gap: 0.5em;
    background: #f1f3f5;
    color: #adb5bd;
    border-radius: 0.7em;
    padding: 0.5em 1em;
    font-weight: 600;
    font-size: 0.98em;
    border: 1px solid #e0e7ff;
}

/* Empty State */
.empty-state {
    display: flex;
    align-items: center;
    gap: 1.2rem;
    background: #fff;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: clamp(1.2rem, 4vw, 2rem);
    margin: 2.5rem 0;
    color: #adb5bd;
    font-size: 1.1rem;
    justify-content: center;
    text-align: left;
}
.empty-icon {
    font-size: 2.5rem;
    color: #adb5bd;
}

/* Responsive */
@media (max-width: 700px) {
    .participant-card {
        flex-direction: column;
        gap: 1.2rem;
        text-align: center;
        align-items: center;
    }
    .info-list {
        flex-direction: column;
        gap: 0.7rem;
        align-items: center;
    }
    .diploma-card {
        flex-direction: column;
        align-items: stretch;
        text-align: center;
        gap: 1rem;
    }
    .diploma-actions {
        align-items: center;
        min-width: 0;
    }
}
@media (max-width: 480px) {
    .main-content {
        padding: 1rem 0.2rem 0 0.2rem;
    }
    .participant-card, .empty-state {
        padding: 1rem;
    }
    .diploma-card {
        padding: 1rem;
    }
}

/* Animations */
.scroll-reveal {
    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.7s cubic-bezier(.4,0,.2,1), transform 0.7s cubic-bezier(.4,0,.2,1);
}
.scroll-reveal.visible {
    opacity: 1;
    transform: translateY(0);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Scroll reveal for diploma cards
    function revealOnScroll() {
        document.querySelectorAll('.scroll-reveal').forEach(function(el, i) {
            const rect = el.getBoundingClientRect();
            if(rect.top < window.innerHeight - 60) {
                setTimeout(() => el.classList.add('visible'), i * 80);
            }
        });
    }
    revealOnScroll();
    window.addEventListener('scroll', revealOnScroll);
    // Animate participant card and empty state
    setTimeout(() => {
        document.querySelectorAll('.participant-card, .empty-state').forEach(el => {
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
            el.style.transition = 'opacity 0.7s cubic-bezier(.4,0,.2,1), transform 0.7s cubic-bezier(.4,0,.2,1)';
        });
    }, 200);
});
</script>
@endsection
