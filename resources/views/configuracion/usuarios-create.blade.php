<x-layout>
    <x-slot:title>VRZ-Usuarios| Sistema de Gestión</x-slot:title>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-gradient-primary text-white p-4 border-0">
                    <div class="d-flex align-items-center gap-3">
                        
                        <div>
                            <h4 class="mb-0 fw-bold">{{ isset($user) ? 'Editar Usuario' : 'Nuevo Usuario' }}</h4>
                            <p class="opacity-75 mb-0 small">{{ isset($user) ? 'Actualiza la información del usuario' : 'Registra un nuevo usuario en el sistema' }}</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-lg-5">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <strong>Por favor, corrige los siguientes errores:</strong>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ isset($user) ? route('usuarios.update', $user) : route('usuarios.store') }}">
                        @csrf
                        @if(isset($user))
                            @method('PUT')
                        @endif

                        <div class="row g-3">
                            <!-- Nombre -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold required-field">Nombre</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required>
                            </div>

                            <!-- Apellidos -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Apellidos</label>
                                <input type="text" name="apellidos" class="form-control" value="{{ old('apellidos', $user->apellidos ?? '') }}">
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold required-field">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required>
                            </div>

                            <!-- Cédula -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Cédula/RIF</label>
                                <input type="text" name="cedula" class="form-control" value="{{ old('cedula', $user->cedula ?? '') }}">
                            </div>

                            <!-- Teléfono -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Teléfono</label>
                                <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $user->telefono ?? '') }}">
                            </div>

                            <!-- Cargo -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Cargo</label>
                                <input type="text" name="cargo" class="form-control" value="{{ old('cargo', $user->cargo ?? '') }}">
                            </div>

                            <!-- Departamento -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Departamento</label>
                                <input type="text" name="departamento" class="form-control" value="{{ old('departamento', $user->departamento ?? '') }}">
                            </div>

                            <!-- Rol -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold required-field">Rol</label>
                                <select name="rol" class="form-select" required>
                                    <option value="usuario" {{ (old('rol', $user->rol ?? '') == 'usuario') ? 'selected' : '' }}>Usuario</option>
                                    <option value="tecnico" {{ (old('rol', $user->rol ?? '') == 'tecnico') ? 'selected' : '' }}>Técnico</option>
                                    <option value="supervisor" {{ (old('rol', $user->rol ?? '') == 'supervisor') ? 'selected' : '' }}>Supervisor</option>
                                    <option value="gerente" {{ (old('rol', $user->rol ?? '') == 'gerente') ? 'selected' : '' }}>Gerente</option>
                                    <option value="admin" {{ (old('rol', $user->rol ?? '') == 'admin') ? 'selected' : '' }}>Administrador</option>
                                </select>
                            </div>

                            <!-- Estado -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold required-field">Estado</label>
                                <select name="estado" class="form-select" required>
                                    <option value="activo" {{ (old('estado', $user->estado ?? '') == 'activo') ? 'selected' : '' }}>Activo</option>
                                    <option value="inactivo" {{ (old('estado', $user->estado ?? '') == 'inactivo') ? 'selected' : '' }}>Inactivo</option>
                                    <option value="suspendido" {{ (old('estado', $user->estado ?? '') == 'suspendido') ? 'selected' : '' }}>Suspendido</option>
                                </select>
                            </div>

                            <!-- Contraseña (solo si es nuevo o se quiere cambiar) -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold {{ !isset($user) ? 'required-field' : '' }}">
                                    Contraseña
                                </label>
                                <input type="password" name="password" class="form-control" placeholder="{{ isset($user) ? 'Dejar en blanco para no cambiar' : 'Mínimo 8 caracteres' }}" {{ !isset($user) ? 'required' : '' }}>
                                @if(isset($user))
                                    <small class="text-muted">Dejar en blanco para mantener la contraseña actual</small>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold {{ !isset($user) ? 'required-field' : '' }}">
                                    Confirmar Contraseña
                                </label>
                                <input type="password" name="password_confirmation" class="form-control" {{ !isset($user) ? 'required' : '' }}>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i>
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>
                                {{ isset($user) ? 'Actualizar Usuario' : 'Crear Usuario' }}
                            </button>
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
    .required-field::after {
        content: " *";
        color: #dc3545;
        font-weight: bold;
    }
</style>
</x-layout>