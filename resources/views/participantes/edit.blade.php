@extends('layouts.app')

@section('content')
<div class="edit-participant-container">
    <!-- Encabezado con animación -->
    <div class="form-header">
        <h1 class="form-title animated-text">
            <span class="icon-circle">✏️</span>
            Editar Participante de 
            <span class="highlight-text">"{{ $capacitacion->nombre }}"</span>
        </h1>
        <p class="form-subtitle">Actualice la información del participante según sea necesario</p>
    </div>

    <!-- Alertas -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show animated-alert" role="alert">
            <div class="alert-content">
                <i class="fas fa-check-circle alert-icon"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('warning'))
        <div class="alert alert-warning alert-dismissible fade show animated-alert" role="alert">
            <div class="alert-content">
                <i class="fas fa-exclamation-triangle alert-icon"></i>
                <span>{{ session('warning') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Formulario principal -->
    <div class="form-card glassmorphism-card">
        <form id="form-participante" action="{{ route('participantes.update', [$capacitacion->id, $participante->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-grid">
                <!-- Sección 1: Información personal -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-user section-icon"></i>
                        Información Personal
                    </h3>
                    
                    <div class="form-group floating-form-group">
                        <input type="text" class="form-control floating-input" name="nombre_completo" id="nombre_completo" value="{{ $participante->nombre_completo }}" required>
                        <label for="nombre_completo" class="floating-label">Nombre Completo</label>
                        <i class="fas fa-user input-icon"></i>
                    </div>
                    
                    <div class="form-group floating-form-group">
                        <input type="email" class="form-control floating-input" name="correo" id="correo" value="{{ $participante->correo }}" required>
                        <label for="correo" class="floating-label">Correo Electrónico</label>
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                    
                    <div class="form-group floating-form-group">
                        <input type="text" class="form-control floating-input" name="telefono" id="telefono" value="{{ $participante->telefono }}" required>
                        <label for="telefono" class="floating-label">Teléfono</label>
                        <i class="fas fa-phone input-icon"></i>
                    </div>
                    
                    <div class="form-group floating-form-group">
                        <input type="text" class="form-control floating-input" name="identidad" id="identidad" value="{{ $participante->identidad }}" placeholder=" " required>
                        <label for="identidad" class="floating-label">Identidad (sin guiones)</label>
                        <i class="fas fa-id-card input-icon"></i>
                    </div>
                    
                    <div class="row g-2">
                        <div class="col-md-6 form-group floating-form-group">
                            <input type="number" class="form-control floating-input" name="edad" id="edad" value="{{ $participante->edad }}" required>
                            <label for="edad" class="floating-label">Edad</label>
                            <i class="fas fa-birthday-cake input-icon"></i>
                        </div>
                        
                        <div class="col-md-6 form-group floating-form-group">
                            <select class="form-control floating-select" name="genero" id="genero" required>
                                <option value=""></option>
                                <option value="Masculino" {{ $participante->genero == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                <option value="Femenino" {{ $participante->genero == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                <option value="Otro" {{ $participante->genero == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                            <label for="genero" class="floating-label">Género</label>
                            <i class="fas fa-venus-mars input-icon"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Sección 2: Información profesional -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-briefcase section-icon"></i>
                        Información Profesional
                    </h3>
                    
                    <div class="form-group floating-form-group">
                        <input type="text" class="form-control floating-input" name="empresa" id="empresa" value="{{ $participante->empresa }}">
                        <label for="empresa" class="floating-label">Empresa</label>
                        <i class="fas fa-building input-icon"></i>
                    </div>
                    
                    <div class="form-group floating-form-group">
                        <input type="text" class="form-control floating-input" name="puesto" id="puesto" value="{{ $participante->puesto }}">
                        <label for="puesto" class="floating-label">Puesto</label>
                        <i class="fas fa-user-tie input-icon"></i>
                    </div>
                    
                    <div class="form-group floating-form-group">
                        <select class="form-control floating-select" name="nivel_educativo" id="nivel_educativo" required>
                            <option value=""></option>
                            <option value="Universitaria Completa" {{ $participante->nivel_educativo == 'Universitaria Completa' ? 'selected' : '' }}>Universitaria Completa</option>
                            <option value="Universitaria Incompleta" {{ $participante->nivel_educativo == 'Universitaria Incompleta' ? 'selected' : '' }}>Universitaria Incompleta</option>
                            <option value="Técnico Completo" {{ $participante->nivel_educativo == 'Técnico Completo' ? 'selected' : '' }}>Técnico Completo</option>
                            <option value="Técnico Incompleto" {{ $participante->nivel_educativo == 'Técnico Incompleto' ? 'selected' : '' }}>Técnico Incompleto</option>
                            <option value="Secundaria Completa" {{ $participante->nivel_educativo == 'Secundaria Completa' ? 'selected' : '' }}>Secundaria Completa</option>
                            <option value="Secundaria Incompleta" {{ $participante->nivel_educativo == 'Secundaria Incompleta' ? 'selected' : '' }}>Secundaria Incompleta</option>
                            <option value="Primaria Completa" {{ $participante->nivel_educativo == 'Primaria Completa' ? 'selected' : '' }}>Primaria Completa</option>
                            <option value="Primaria Incompleta" {{ $participante->nivel_educativo == 'Primaria Incompleta' ? 'selected' : '' }}>Primaria Incompleta</option>
                        </select>
                        <label for="nivel_educativo" class="floating-label">Nivel Educativo</label>
                        <i class="fas fa-graduation-cap input-icon"></i>
                    </div>
                </div>
                
                <!-- Sección 3: Ubicación -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-map-marker-alt section-icon"></i>
                        Ubicación
                    </h3>
                    
                    <div class="form-group floating-form-group">
                        <input type="text" class="form-control floating-input" name="municipio" id="municipio" value="{{ $participante->municipio }}" required>
                        <label for="municipio" class="floating-label">Municipio</label>
                        <i class="fas fa-city input-icon"></i>
                    </div>
                    
                    <div class="form-group floating-form-group">
                        <input type="text" class="form-control floating-input" name="ciudad" id="ciudad" value="{{ $participante->ciudad }}" required>
                        <label for="ciudad" class="floating-label">Ciudad</label>
                        <i class="fas fa-map input-icon"></i>
                    </div>
                    
                    <div class="form-group floating-form-group">
                        <select name="afiliado" id="afiliado" class="form-control floating-select" required>
                            <option value="0" {{ !$participante->afiliado ? 'selected' : '' }}>No</option>
                            <option value="1" {{ $participante->afiliado ? 'selected' : '' }}>Sí</option>
                        </select>
                        <label for="afiliado" class="floating-label">¿Es afiliado?</label>
                        <i class="fas fa-id-badge input-icon"></i>
                    </div>
                </div>
                
                <!-- Sección 4: Pago (condicional) -->
                @if (strtolower($capacitacion->medio) === 'pago')
                <div class="form-section payment-section">
                    <h3 class="section-title">
                        <i class="fas fa-credit-card section-icon"></i>
                        Información de Pago
                    </h3>
                    
                    <div class="form-group file-upload-group">
                        <label class="file-upload-label">
                            <input type="file" name="comprobante" id="comprobante" accept="image/*,application/pdf" class="file-upload-input">
                            <span class="file-upload-button">
                                <i class="fas fa-cloud-upload-alt"></i> Cambiar Comprobante
                            </span>
                            @if ($participante->comprobante)
                                <span class="file-upload-text">
                                    Actual: <a href="{{ asset('storage/' . $participante->comprobante) }}" target="_blank" class="file-link">Ver comprobante</a>
                                </span>
                            @else
                                <span class="file-upload-text">Formatos: JPG, PNG, PDF</span>
                            @endif
                        </label>
                    </div>
                    
                    <div class="payment-details">
                        <div class="payment-row">
                            <span class="payment-label">Precio Base:</span>
                            <span class="payment-value" id="precio-display">L. {{ number_format($participante->precio, 2) }}</span>
                            <input type="hidden" id="precio" name="precio" value="{{ $participante->precio }}">
                        </div>
                        
                        <div class="payment-row">
                            <span class="payment-label">ISV (15%):</span>
                            <span class="payment-value" id="isv-display">L. {{ number_format($participante->isv, 2) }}</span>
                            <input type="hidden" id="isv" name="isv" value="{{ $participante->isv }}">
                        </div>
                        
                        <div class="payment-row total-row">
                            <span class="payment-label">Total a Pagar:</span>
                            <span class="payment-value" id="total-display">L. {{ number_format($participante->total, 2) }}</span>
                            <input type="hidden" id="total" name="total" value="{{ $participante->total }}">
                        </div>
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Botones de acción -->
            <div class="form-actions">
                <button type="submit" class="btn btn-submit">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                
                <a href="{{ route('capacitaciones.participantes', $capacitacion->id) }}" class="btn btn-back">
                    <i class="fas fa-arrow-left"></i> Volver a Lista
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar campos con etiquetas flotantes
    const floatingInputs = document.querySelectorAll('.floating-input');
    floatingInputs.forEach(input => {
        if (input.value) {
            input.parentNode.classList.add('focused');
        }
        
        input.addEventListener('focus', function() {
            this.parentNode.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            if (!this.value) {
                this.parentNode.classList.remove('focused');
            }
        });
    });
    
    // Inicializar selects con etiquetas flotantes
    const floatingSelects = document.querySelectorAll('.floating-select');
    floatingSelects.forEach(select => {
        if (select.value) {
            select.parentNode.classList.add('focused');
        }
        
        select.addEventListener('change', function() {
            if (this.value) {
                this.parentNode.classList.add('focused');
            } else {
                this.parentNode.classList.remove('focused');
            }
        });
    });
    
    // Cálculo de precios
    const afiliado = document.getElementById('afiliado');
    if (afiliado) {
        afiliado.addEventListener('change', calcularTotal);
    }
    
    function calcularTotal() {
        let base = 0;
        let impuesto = 0;

        @if (strtolower($capacitacion->medio) === 'pago')
            const esAfiliado = parseInt(afiliado.value);
            base = esAfiliado ? {{ $capacitacion->precio_afiliado }} : {{ $capacitacion->precio_no_afiliado }};
            impuesto = esAfiliado ? {{ $capacitacion->isv_afiliado }} : {{ $capacitacion->isv_no_afiliado }};
            
            document.getElementById('precio').value = base.toFixed(2);
            document.getElementById('isv').value = impuesto.toFixed(2);
            document.getElementById('total').value = (base + impuesto).toFixed(2);
            
            // Actualizar displays
            document.getElementById('precio-display').textContent = `L. ${base.toFixed(2)}`;
            document.getElementById('isv-display').textContent = `L. ${impuesto.toFixed(2)}`;
            document.getElementById('total-display').textContent = `L. ${(base + impuesto).toFixed(2)}`;
        @endif
    }
});
</script>

<!-- Estilos CSS -->
<style>
:root {
    --primary-color: #4361ee;
    --primary-hover: #3a56d4;
    --secondary-color: #3f37c9;
    --success-color: #4cc9f0;
    --warning-color: #f8961e;
    --danger-color: #f72585;
    --light-color: #f8f9fa;
    --dark-color: #212529;
    --text-color: #2b2d42;
    --text-muted: #6c757d;
    --border-color: #e9ecef;
    --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    --card-hover-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
    --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    --border-radius: 12px;
    --border-radius-sm: 8px;
    --glass-bg: rgba(255, 255, 255, 0.9);
    --glass-border: 1px solid rgba(255, 255, 255, 0.2);
    --glass-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
}

/* Estructura principal */
.edit-participant-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1.5rem;
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

/* Encabezado */
.form-header {
    text-align: center;
    margin-bottom: 2.5rem;
}

.form-title {
    font-size: 2.25rem;
    font-weight: 700;
    color: var(--dark-color);
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 0.75rem;
}

.highlight-text {
    color: var(--warning-color);
    background: rgba(248, 150, 30, 0.1);
    padding: 0.25rem 1rem;
    border-radius: 50px;
    display: inline-block;
}

.icon-circle {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 50px;
    height: 50px;
    background: var(--warning-color);
    color: white;
    border-radius: 50%;
    font-size: 1.5rem;
}

.form-subtitle {
    color: var(--text-muted);
    font-size: 1.1rem;
    max-width: 700px;
    margin: 0 auto;
}

/* Alertas */
.animated-alert {
    animation: fadeInDown 0.5s ease;
    border: none;
    border-radius: var(--border-radius-sm);
    box-shadow: var(--card-shadow);
    margin-bottom: 2rem;
}

.alert-content {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.alert-icon {
    font-size: 1.25rem;
}

/* Tarjetas */
.glassmorphism-card {
    background: var(--glass-bg);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: var(--glass-border);
    border-radius: var(--border-radius);
    box-shadow: var(--glass-shadow);
    padding: 2rem;
    margin-bottom: 2rem;
    transition: var(--transition);
}

.glassmorphism-card:hover {
    box-shadow: var(--card-hover-shadow);
}

/* Formulario */
.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.form-section {
    background: rgba(248, 249, 250, 0.7);
    border-radius: var(--border-radius-sm);
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.section-title {
    font-size: 1.25rem;
    color: var(--warning-color);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.section-icon {
    font-size: 1.1rem;
}

/* Grupos de formulario */
.form-group {
    margin-bottom: 1.25rem;
    position: relative;
}

.floating-form-group {
    position: relative;
    margin-bottom: 1.5rem;
}

.floating-input, .floating-select {
    width: 100%;
    padding: 1rem 1rem 1rem 2.5rem;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-sm);
    background: white;
    font-size: 1rem;
    transition: var(--transition);
}

.floating-input:focus, .floating-select:focus {
    border-color: var(--warning-color);
    box-shadow: 0 0 0 3px rgba(248, 150, 30, 0.15);
    outline: none;
}

.floating-label {
    position: absolute;
    top: 1rem;
    left: 2.5rem;
    color: var(--text-muted);
    pointer-events: none;
    transition: var(--transition);
}

.floating-input:focus ~ .floating-label,
.floating-input:not(:placeholder-shown) ~ .floating-label,
.floating-select:focus ~ .floating-label,
.floating-select:not([value=""]) ~ .floating-label,
.floating-form-group.focused .floating-label {
    top: -0.6rem;
    left: 1rem;
    font-size: 0.75rem;
    background: white;
    padding: 0 0.5rem;
    color: var(--warning-color);
}

.input-icon {
    position: absolute;
    left: 1rem;
    top: 1rem;
    color: var(--warning-color);
    font-size: 1rem;
}

/* Sección de pago */
.payment-section {
    background: rgba(248, 150, 30, 0.05);
    border: 1px solid rgba(248, 150, 30, 0.1);
}

.payment-details {
    background: white;
    border-radius: var(--border-radius-sm);
    padding: 1rem;
    margin-top: 1rem;
}

.payment-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.75rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px dashed var(--border-color);
}

.payment-row:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.total-row {
    font-weight: 600;
    color: var(--warning-color);
}

.payment-label {
    color: var(--text-muted);
}

.payment-value {
    font-weight: 500;
}

/* Subida de archivos */
.file-upload-group {
    margin-bottom: 1.5rem;
}

.file-upload-label {
    display: block;
    cursor: pointer;
}

.file-upload-input {
    display: none;
}

.file-upload-button {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--warning-color);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: var(--border-radius-sm);
    transition: var(--transition);
}

.file-upload-button:hover {
    background: #e07e0c;
    transform: translateY(-2px);
}

.file-upload-text {
    display: block;
    font-size: 0.8rem;
    color: var(--text-muted);
    margin-top: 0.5rem;
}

.file-link {
    color: var(--warning-color);
    text-decoration: none;
    font-weight: 500;
}

.file-link:hover {
    text-decoration: underline;
}

/* Botones de acción */
.form-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    justify-content: center;
    margin-top: 2rem;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: var(--border-radius-sm);
    font-weight: 500;
    transition: var(--transition);
    border: none;
    cursor: pointer;
}

.btn-submit {
    background: var(--warning-color);
    color: white;
}

.btn-submit:hover {
    background: #e07e0c;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(248, 150, 30, 0.2);
}

.btn-back {
    background: var(--light-color);
    color: var(--text-color);
}

.btn-back:hover {
    background: #e9ecef;
    transform: translateY(-2px);
}

/* Animaciones */
@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animated-text {
    animation: fadeInDown 0.5s ease;
}

/* Responsive */
@media (max-width: 768px) {
    .form-title {
        font-size: 1.8rem;
        flex-direction: column;
    }
    
    .icon-circle {
        width: 40px;
        height: 40px;
        font-size: 1.25rem;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 576px) {
    .edit-participant-container {
        padding: 1.5rem 1rem;
    }
    
    .form-title {
        font-size: 1.5rem;
    }
    
    .form-subtitle {
        font-size: 1rem;
    }
}
</style>

<!-- CDNs -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection