@extends('layouts.app')

@section('title', 'Soporte')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <!-- Header -->
                <div class="card-header bg-gradient-primary text-white p-4 border-0">
                    <div class="d-flex align-items-center gap-3">
                        
                        <div>
                            <h4 class="mb-0 fw-bold">Centro de Soporte</h4>
                            <p class="opacity-75 mb-0 small">Estamos aquí para ayudarte</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-lg-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-life-ring text-primary" style="font-size: 64px;"></i>
                        <h5 class="mt-3 fw-bold">¿Necesitas ayuda?</h5>
                        <p class="text-muted">Elige una de las siguientes opciones para contactarnos</p>
                    </div>

                    <div class="row g-4">
                        <!-- Email -->
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm rounded-4 text-center p-4 h-100">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                                     style="width: 60px; height: 60px;">
                                    <i class="fas fa-envelope text-primary fs-3"></i>
                                </div>
                                <h6 class="mt-3 fw-bold">Correo Electrónico</h6>
                                <p class="text-muted small">Escríbenos y te responderemos</p>
                                <a href="mailto:soporte@empresa.com" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-envelope me-1"></i>
                                    soporte@empresa.com
                                </a>
                            </div>
                        </div>

                        <!-- Teléfono -->
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm rounded-4 text-center p-4 h-100">
                                <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                                     style="width: 60px; height: 60px;">
                                    <i class="fas fa-phone text-success fs-3"></i>
                                </div>
                                <h6 class="mt-3 fw-bold">Llamada Telefónica</h6>
                                <p class="text-muted small">Atención personalizada</p>
                                <a href="tel:+582125551212" class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-phone me-1"></i>
                                    +58 212-555-1212
                                </a>
                            </div>
                        </div>

                        <!-- WhatsApp -->
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm rounded-4 text-center p-4 h-100">
                                <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                                     style="width: 60px; height: 60px;">
                                    <i class="fab fa-whatsapp text-success fs-3"></i>
                                </div>
                                <h6 class="mt-3 fw-bold">WhatsApp</h6>
                                <p class="text-muted small">Respuesta inmediata</p>
                                <a href="https://wa.me/584121234567?text=Hola%20necesito%20ayuda" 
                                   target="_blank" 
                                   class="btn btn-outline-success btn-sm">
                                    <i class="fab fa-whatsapp me-1"></i>
                                    +58 412-123-4567
                                </a>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Horario -->
                    <div class="text-center">
                        <h6 class="fw-bold">
                            <i class="fas fa-clock me-2 text-primary"></i>
                            Horario de Atención
                        </h6>
                        <p class="text-muted small mb-0">
                            Lunes a Viernes: 8:00 am - 5:00 pm
                        </p>
                        <p class="text-muted small">
                            Sábados: 8:00 am - 12:00 pm
                        </p>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Volver al Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .rounded-4 { 
        border-radius: 1rem !important; 
    }
    
    .card {
        animation: fadeInUp 0.5s ease;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .dark-mode .card {
        background: #1a1a2e !important;
        color: #e2e8f0 !important;
    }
    
    .dark-mode .card-header {
        background: linear-gradient(135deg, #1e293b, #0f172a) !important;
    }
    
    .dark-mode .border-0 {
        border-color: #2d2d44 !important;
    }
</style>
@endsection