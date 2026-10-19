@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <!-- Header -->
                <div class="card-header bg-gradient-primary text-white p-4 border-0">
                    <div class="d-flex align-items-center gap-3">
                        
                        <div>
                            <h4 class="fw-bold mb-1">Crear Cuenta</h4>
                            <p class="opacity-75 mb-0 small">Regístrate para acceder al sistema</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-lg-5">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Nombre -->
                        <div class="form-group mb-3">
                            <label for="name" class="form-label fw-semibold">
                                <i class="fas fa-user text-primary me-2"></i>
                                Nombre Completo
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-user text-secondary"></i>
                                </span>
                                <input id="name" 
                                       type="text" 
                                       class="form-control border-start-0 @error('name') is-invalid @enderror" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       required 
                                       autocomplete="name"
                                       placeholder="Tu nombre completo">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="form-group mb-3">
                            <label for="email" class="form-label fw-semibold">
                                <i class="fas fa-envelope text-primary me-2"></i>
                                Correo Electrónico
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-envelope text-secondary"></i>
                                </span>
                                <input id="email" 
                                       type="email" 
                                       class="form-control border-start-0 @error('email') is-invalid @enderror" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required 
                                       autocomplete="email"
                                       placeholder="usuario@empresa.com">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Contraseña -->
                        <div class="form-group mb-3">
                            <label for="password" class="form-label fw-semibold">
                                <i class="fas fa-lock text-primary me-2"></i>
                                Contraseña
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-lock text-secondary"></i>
                                </span>
                                <input id="password" 
                                       type="password" 
                                       class="form-control border-start-0 @error('password') is-invalid @enderror" 
                                       name="password" 
                                       required 
                                       autocomplete="new-password"
                                       placeholder="Mínimo 8 caracteres">
                                <button type="button" 
                                        class="btn btn-outline-secondary border-start-0" 
                                        onclick="togglePasswordVisibility('password')">
                                    <i class="fas fa-eye" id="passwordIcon"></i>
                                </button>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                La contraseña debe tener al menos 8 caracteres
                            </small>
                        </div>

                        <!-- Confirmar Contraseña -->
                        <div class="form-group mb-4">
                            <label for="password-confirm" class="form-label fw-semibold">
                                <i class="fas fa-check-circle text-primary me-2"></i>
                                Confirmar Contraseña
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-check-circle text-secondary"></i>
                                </span>
                                <input id="password-confirm" 
                                       type="password" 
                                       class="form-control border-start-0" 
                                       name="password_confirmation" 
                                       required 
                                       autocomplete="new-password"
                                       placeholder="Repite tu contraseña">
                            </div>
                        </div>

                        <!-- Términos -->
                        <div class="form-group mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" required id="terms">
                                <label class="form-check-label small" for="terms">
                                    Acepto los 
                                    <a href="#" class="text-primary text-decoration-none">Términos y Condiciones</a>
                                    y la 
                                    <a href="#" class="text-primary text-decoration-none">Política de Privacidad</a>
                                </label>
                            </div>
                        </div>

                        <!-- Botón Registro -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                                <i class="fas fa-user-plus me-2"></i>
                                Registrarse
                            </button>
                        </div>

                        <!-- Login -->
                        <div class="text-center mt-3">
                            <small class="text-muted">
                                ¿Ya tienes cuenta? 
                                <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">
                                    Inicia Sesión
                                </a>
                            </small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .rounded-4 { border-radius: 1rem !important; }
    
    .form-control:focus {
        border-color: #764ba2;
        box-shadow: 0 0 0 0.2rem rgba(118, 75, 162, 0.15);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(118, 75, 162, 0.4) !important;
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
</style>

<script>
    function togglePasswordVisibility(inputId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById('passwordIcon');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fas fa-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'fas fa-eye';
        }
    }
</script>
@endsection