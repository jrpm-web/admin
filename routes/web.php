<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AlertaController;
use App\Http\Controllers\IncidenciaController;
use Illuminate\Support\Facades\Route;

// ============================================================
// RUTAS DE AUTENTICACIÓN PERSONALIZADAS
// ============================================================

// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Login automático (API)
Route::post('/login-auto', [LoginController::class, 'autoLogin'])->name('login.auto');
Route::get('/login-auto/{email}', function ($email) {
    return redirect('/login')->with('auto_email', $email);
})->name('login.auto.get');

// Registro
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/logout', [LoginController::class, 'logout']);

// ============================================================
// RUTAS DE PERFIL DE USUARIO (Protegidas)
// ============================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ============================================================
// RUTAS DE GESTIÓN DE USUARIOS (Protegidas)
// ============================================================
Route::middleware(['auth'])->group(function () {
    Route::resource('usuarios', UsuarioController::class);
    Route::post('/usuarios/{user}/toggle-status', [UsuarioController::class, 'toggleStatus'])->name('usuarios.toggle-status');
    Route::get('/usuarios/search', [UsuarioController::class, 'search'])->name('usuarios.search');
});

// ============================================================
// RUTAS DE PROVEEDORES (Protegidas)
// ============================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/proveedores/search', [ProveedorController::class, 'search'])->name('proveedores.search');

    // Reportes
    Route::get('proveedores/reportes', [ProveedorController::class, 'reportes'])->name('proveedores.reportes');
    Route::get('proveedores/reportes/exportar', [ProveedorController::class, 'exportarReporte'])->name('proveedores.reportes.exportar');

    // ✅ Exportar PDF (POST porque recibe imágenes base64 de Chart.js)
    Route::post('proveedores/reportes/pdf', [ProveedorController::class, 'exportarPdf'])->name('proveedores.reportes.pdf');

    // Resource (debe ir al final para que no atrape las rutas específicas de arriba)
    Route::resource('proveedores', ProveedorController::class);
    Route::post('/proveedores/{id}/toggle-status', [ProveedorController::class, 'toggleStatus'])->name('proveedores.toggle-status');
});

// ============================================================
// RUTAS DE CATEGORÍAS (Protegidas)
// ============================================================
Route::middleware(['auth'])->group(function () {
    Route::resource('categorias', CategoriaController::class);
    Route::post('/categorias/{id}/toggle-status', [CategoriaController::class, 'toggleStatus'])->name('categorias.toggle-status');
    Route::get('/categorias/search', [CategoriaController::class, 'search'])->name('categorias.search');
});

// ============================================================
// RUTAS DE PRODUCTOS (Protegidas)
// ============================================================
Route::middleware(['auth'])->group(function () {
    Route::resource('productos', ProductoController::class);
    Route::get('/productos/search', [ProductoController::class, 'search'])->name('productos.search');
    Route::get('/productos/por-proveedor/{proveedorId}', [ProductoController::class, 'getByProveedor'])->name('productos.por-proveedor');
});

// ============================================================
// ✅ RUTAS DE FACTURAS (Protegidas)
// ============================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/facturas/por-mes', [FacturaController::class, 'getFacturasPorMes'])->name('facturas.por-mes');
    Route::get('/facturas/grafica-data', [FacturaController::class, 'getGraficaData'])->name('facturas.grafica-data');
    Route::get('/facturas/reportes', [FacturaController::class, 'reportes'])->name('facturas.reportes');
    Route::get('/facturas/estadisticas', [FacturaController::class, 'estadisticas'])->name('facturas.estadisticas');
    Route::resource('facturas', FacturaController::class);
});

// ============================================================
// ✅ RUTAS DE ALERTAS (Protegidas)
// ============================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/alertas/listar', [AlertaController::class, 'getAlertas'])->name('alertas.listar');
    Route::post('/alertas/crear', [AlertaController::class, 'store'])->name('alertas.crear');
    Route::post('/alertas/{id}/leer', [AlertaController::class, 'marcarLeida'])->name('alertas.leer');
    Route::post('/alertas/marcar-todas-leidas', [AlertaController::class, 'marcarTodasLeidas'])->name('alertas.marcar-todas');
    Route::delete('/alertas/{id}', [AlertaController::class, 'destroy'])->name('alertas.eliminar');
    Route::put('/alertas/{id}', [AlertaController::class, 'update'])->name('alertas.actualizar');
    Route::get('/alertas/actualizar', [AlertaController::class, 'actualizarAlertas'])->name('alertas.actualizar');
});

// ============================================================
// ✅ RUTAS DE INCIDENCIAS (Protegidas)
// ============================================================
Route::middleware(['auth'])->group(function () {
    Route::prefix('incidencias')->name('incidencias.')->group(function () {
        // Rutas específicas (deben ir ANTES del resource)
        Route::get('/',     [IncidenciaController::class, 'index'])->name('abiertas');
        Route::get('/abiertas',     [IncidenciaController::class, 'abiertas'])->name('abiertas');
        Route::get('/finalizadas',  [IncidenciaController::class, 'finalizadas'])->name('finalizadas');
        Route::get('/asignadas',    [IncidenciaController::class, 'asignadas'])->name('asignadas');
        Route::get('/search',       [IncidenciaController::class, 'search'])->name('search');
        Route::get('/estadisticas', [IncidenciaController::class, 'estadisticas'])->name('estadisticas');
        Route::get('/reportes',     [IncidenciaController::class, 'reportes'])->name('reportes');
        Route::get('/badge-count',  [IncidenciaController::class, 'badgeCount'])->name('badge-count');

        // Acciones especiales
        Route::post('/{incidencia}/cerrar',   [IncidenciaController::class, 'cerrar'])->name('cerrar');
        Route::post('/{incidencia}/reabrir',  [IncidenciaController::class, 'reabrir'])->name('reabrir');
        Route::post('/{incidencia}/asignar',  [IncidenciaController::class, 'asignar'])->name('asignar');
        Route::post('/{incidencia}/comentar', [IncidenciaController::class, 'comentar'])->name('comentar');

        // CRUD
        Route::resource('/', IncidenciaController::class)
             ->parameters(['' => 'incidencia'])
             ->names([
                'index'   => 'index',
                'create'  => 'create',
                'store'   => 'store',
                'show'    => 'show',
                'edit'    => 'edit',
                'update'  => 'update',
                'destroy' => 'destroy',
             ]);
    });
});

// ============================================================
// ✅ RUTAS DEL DASHBOARD (Protegidas)
// ============================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/data', [DashboardController::class, 'getData'])->name('dashboard.data');
    Route::get('/dashboard/estadisticas', [DashboardController::class, 'getEstadisticas'])->name('dashboard.estadisticas');
});

// ============================================================
// RUTAS DE SOPORTE (Protegidas)
// ============================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/soporte', function () {
        return view('soporte.index');
    })->name('soporte');
});

// ============================================================
// RUTAS DEL SISTEMA (Todas protegidas)
// ============================================================
Route::middleware(['auth'])->group(function () {

    // Inventario
    Route::prefix('inventario')->group(function () {
        Route::get('/movimientos/entradas', function() {
            return view('inventario.movimientos.entradas');
        });
        Route::get('/movimientos/salidas', function() {
            return view('inventario.movimientos.salidas');
        });
        Route::get('/movimientos/historial', function() {
            return view('inventario.movimientos.historial');
        });
        Route::get('/reportes/stock', function() {
            return view('inventario.reportes.stock');
        });
        Route::get('/reportes/valoracion', function() {
            return view('inventario.reportes.valoracion');
        });
        Route::get('/reportes/mas-vendidos', function() {
            return view('inventario.reportes.mas-vendidos');
        });
        Route::get('/reportes/graficos', function() {
            return view('inventario.reportes.graficos');
        });
        Route::get('/ajustes', function() {
            return view('inventario.ajustes');
        });
        Route::get('/importar', function() {
            return view('inventario.importar');
        });
        Route::get('/exportar', function() {
            return view('inventario.exportar');
        });
        Route::get('/escaneo', function() {
            return view('inventario.escaneo');
        });
    });

    // Configuración
    Route::prefix('configuracion')->group(function () {
        Route::get('/empresa', function() {
            return view('configuracion.empresa');
        });
        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('configuracion.usuarios');
        Route::get('/impuestos', function() {
            return view('configuracion.impuestos');
        });
    });

    // Reportes
    Route::prefix('reportes')->group(function () {
        Route::get('/facturas', function() {
            return view('reportes.facturas');
        });
        Route::get('/proveedores', function() {
            return view('reportes.proveedores');
        });
        Route::get('/financieros', function() {
            return view('reportes.financieros');
        });
    });

    // Herramientas
    Route::prefix('herramientas')->group(function () {
        Route::get('/importar', function() {
            return view('herramientas.importar');
        });
        Route::get('/exportar', function() {
            return view('herramientas.exportar');
        });
        Route::get('/limpiar', function() {
            return view('herramientas.limpiar');
        });
    });
});

// ============================================================
// RUTA DE INICIO
// ============================================================
Route::get('/', function () {
    return redirect('/dashboard');
});