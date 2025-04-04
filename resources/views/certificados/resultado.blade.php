@extends('layouts.app')

@section('content')
<div class="certificates-container">
    <!-- Hero Section -->
    <div class="certificates-hero text-center py-5">
        <div class="container">
            <h1 class="hero-title animate__animated animate__fadeInDown">
                <i class="fas fa-certificate hero-icon"></i> Certificados Encontrados
            </h1>
            <p class="hero-subtitle animate__animated animate__fadeIn animate__delay-1s">
                Verifica y descarga tus diplomas de capacitación
            </p>
        </div>
    </div>

    <div class="container py-4">
        <!-- Resultado de búsqueda -->
        @if(!$participante)
        <div class="alert alert-warning alert-dismissible fade show animate__animated animate__shakeX">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle me-3 fs-4"></i>
                <div>
                    <h5 class="alert-heading mb-1">¡No encontramos resultados!</h5>
                    <p class="mb-0">No se encontró ningún participante con esa identidad.</p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @else
        <!-- Tarjeta de información del participante -->
        <div class="card participant-card mb-5 animate__animated animate__fadeIn">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center mb-3">
                            <div class="participant-avatar bg-primary">
                                <i class="fas fa-user"></i>
                            </div>
                            <h2 class="participant-name mb-0 ms-3">{{ $participante->nombre_completo }}</h2>
                        </div>
                        
                        <div class="participant-details">
                            <div class="detail-item">
                                <i class="fas fa-envelope"></i>
                                <span>{{ $participante->correo ?: 'No especificado' }}</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-id-card"></i>
                                <span>{{ $participante->identidad }}</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-award"></i>
                                <span>{{ $participante->capacitaciones->count() }} capacitación(es)</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <div class="certificate-badge">
                            <i class="fas fa-certificate"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Listado de capacitaciones -->
        @if($participante->capacitaciones->isEmpty())
        <div class="alert alert-info animate__animated animate__fadeIn">
            <div class="d-flex align-items-center">
                <i class="fas fa-info-circle me-3 fs-4"></i>
                <div>
                    <h5 class="alert-heading mb-1">Sin capacitaciones registradas</h5>
                    <p class="mb-0">Este participante no tiene capacitaciones registradas aún.</p>
                </div>
            </div>
        </div>
        @else
        <h3 class="section-title mb-4 animate__animated animate__fadeIn">
            <i class="fas fa-list-ul me-2"></i> Tus Diplomas Disponibles
        </h3>
        
        <div class="row g-4">
            @foreach ($participante->capacitaciones as $cap)
            <div class="col-lg-6 animate__animated animate__fadeInUp" style="animation-delay: {{ $loop->index * 0.1 }}s">
                <div class="certificate-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="certificate-icon">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <span class="badge bg-primary">{{ $loop->iteration }}</span>
                        </div>
                        
                        <h4 class="certificate-title">{{ $cap->nombre }}</h4>
                        
                        <div class="certificate-meta">
                            <div class="meta-item">
                                <i class="fas fa-calendar-day"></i>
                                <span>{{ \Carbon\Carbon::parse($cap->fecha_inicio)->isoFormat('D [de] MMMM [de] YYYY') }}</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-clock"></i>
                                <span>{{ $cap->duracion ?: 'Duración no especificada' }}</span>
                            </div>
                        </div>
                        
                        <div class="d-grid mt-4">
                            <a href="{{ route('certificados.descargar', [$cap->id, $participante->id]) }}" class="btn btn-download">
                                <i class="fas fa-download me-2"></i> Descargar Diploma
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
        @endif
    </div>
</div>

<!-- CDNs para animaciones y estilos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

<style>
:root {
    --primary-color: #4361ee;
    --primary-light: #6c7ef0;
    --secondary-color: #3a0ca3;
    --success-color: #4cc9f0;
    --light-color: #f8f9fa;
    --dark-color: #212529;
    --text-color: #2b2d42;
    --text-light: #8d99ae;
    --shadow-sm: 0 1px 3px rgba(0,0,0,0.12);
    --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
    --shadow-lg: 0 10px 15px rgba(0,0,0,0.1);
    --transition: all 0.3s ease;
}

.certificates-container {
    background-color: #f8fafc;
    min-height: 100vh;
}

/* Hero Section */
.certificates-hero {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    margin-bottom: 2rem;
}

.hero-title {
    font-weight: 700;
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
}

.hero-icon {
    margin-right: 0.5rem;
}

.hero-subtitle {
    font-weight: 300;
    font-size: 1.2rem;
    opacity: 0.9;
}

/* Participant Card */
.participant-card {
    border: none;
    border-radius: 12px;
    box-shadow: var(--shadow-lg);
    overflow: hidden;
    transition: var(--transition);
}

.participant-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.15);
}

.participant-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.participant-name {
    font-weight: 700;
    color: var(--text-color);
}

.participant-details {
    margin-top: 1.5rem;
}

.detail-item {
    display: flex;
    align-items: center;
    margin-bottom: 0.8rem;
    color: var(--text-light);
}

.detail-item i {
    width: 24px;
    color: var(--primary-color);
    margin-right: 0.8rem;
    text-align: center;
}

.certificate-badge {
    font-size: 5rem;
    color: rgba(67, 97, 238, 0.1);
}

/* Certificate Cards */
.certificate-card {
    border: none;
    border-radius: 12px;
    box-shadow: var(--shadow-md);
    transition: var(--transition);
    height: 100%;
    background-color: white;
}

.certificate-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.certificate-icon {
    width: 50px;
    height: 50px;
    background-color: rgba(76, 201, 240, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--success-color);
    font-size: 1.5rem;
}

.certificate-title {
    font-weight: 600;
    color: var(--text-color);
    margin-bottom: 1.5rem;
}

.certificate-meta {
    margin-bottom: 1.5rem;
}

.meta-item {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
    color: var(--text-light);
    font-size: 0.9rem;
}

.meta-item i {
    width: 20px;
    margin-right: 0.5rem;
    color: var(--primary-color);
}

.btn-download {
    background-color: var(--primary-color);
    color: white;
    border: none;
    padding: 0.75rem;
    border-radius: 8px;
    font-weight: 500;
    transition: var(--transition);
}

.btn-download:hover {
    background-color: var(--secondary-color);
    transform: translateY(-2px);
}

/* Alertas personalizadas */
.alert {
    border-radius: 10px;
    border: none;
    box-shadow: var(--shadow-sm);
}

.alert-warning {
    background-color: #fff3cd;
    color: #856404;
}

.alert-info {
    background-color: #e7f5ff;
    color: #0c5460;
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-subtitle {
        font-size: 1rem;
    }
    
    .participant-avatar {
        width: 50px;
        height: 50px;
        font-size: 1.2rem;
    }
    
    .participant-name {
        font-size: 1.3rem;
    }
    
    .certificate-badge {
        font-size: 3rem;
    }
}

@media (max-width: 576px) {
    .hero-title {
        font-size: 1.8rem;
    }
    
    .participant-details {
        margin-top: 1rem;
    }
    
    .detail-item {
        font-size: 0.9rem;
    }
    
    .certificate-title {
        font-size: 1.2rem;
    }
}
</style>

<script>
// Efecto de aparición gradual para las tarjetas
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.animate__animated');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add(entry.target.dataset.animation);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    
    cards.forEach(card => {
        observer.observe(card);
    });
});
</script>
@endsection