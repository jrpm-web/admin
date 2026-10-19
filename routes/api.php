<?php
// routes/web.php - Rutas principales
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Ruta principal del dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

// Rutas de facturas (web)
Route::resource('facturas', InvoiceController::class);
Route::post('/facturas', [InvoiceController::class, 'store'])->name('facturas.store');
Route::get('/facturas/{id}/edit', [InvoiceController::class, 'edit'])->name('facturas.edit');
Route::put('/facturas/{id}', [InvoiceController::class, 'update'])->name('facturas.update');
Route::delete('/facturas/{id}', [InvoiceController::class, 'destroy'])->name('facturas.destroy');

// Rutas de proveedores (web)
Route::resource('proveedores', SupplierController::class);
Route::get('/proveedores/{id}/facturas', [SupplierController::class, 'invoices'])->name('proveedores.facturas');

// Rutas de reportes (web)
Route::get('/reportes', [ReportController::class, 'index'])->name('reportes.index');
Route::post('/reportes/generate', [ReportController::class, 'generate'])->name('reportes.generate');
Route::get('/reportes/export/{type}', [ReportController::class, 'export'])->name('reportes.export');

// Ruta para actualizar datos del dashboard (AJAX)
Route::get('/dashboard/data', [DashboardController::class, 'getData'])->name('dashboard.data');