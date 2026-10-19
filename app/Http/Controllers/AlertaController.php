<?php
// app/Http/Controllers/AlertaController.php

namespace App\Http\Controllers;

use App\Models\Alerta;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AlertaController extends Controller
{
    /**
     * Obtener alertas del dashboard
     */
    public function getAlertas()
    {
        $alertas = Alerta::with(['proveedor', 'creador'])
            ->activas()
            ->orderBy('fecha_limite', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        $totalAlertas = Alerta::activas()->noLeidas()->count();
        
        return response()->json([
            'success' => true,
            'alertas' => $alertas,
            'total_alertas' => $totalAlertas
        ]);
    }

    /**
     * Crear una nueva alerta
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'proveedor_id' => 'required|exists:proveedores,id',
            'mensaje' => 'required|string|max:500',
            'tipo' => 'required|in:recordatorio,vencimiento,alerta',
            'prioridad' => 'required|in:baja,media,alta,critica',
            'fecha_limite' => 'nullable|date|after:today',
            'observaciones' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $alerta = Alerta::create([
                'proveedor_id' => $request->proveedor_id,
                'mensaje' => $request->mensaje,
                'tipo' => $request->tipo,
                'prioridad' => $request->prioridad,
                'fecha_limite' => $request->fecha_limite,
                'observaciones' => $request->observaciones,
                'creado_por' => Auth::id(),
                'leida' => false,
                'activa' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => '✅ Alerta creada exitosamente',
                'alerta' => $alerta->load(['proveedor', 'creador'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Error al crear la alerta: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Marcar alerta como leída
     */
    public function marcarLeida($id)
    {
        try {
            $alerta = Alerta::findOrFail($id);
            $alerta->update(['leida' => true]);

            return response()->json([
                'success' => true,
                'message' => '✅ Alerta marcada como leída'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Error al marcar la alerta: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Marcar todas las alertas como leídas
     */
    public function marcarTodasLeidas()
    {
        try {
            Alerta::activas()->noLeidas()->update(['leida' => true]);

            return response()->json([
                'success' => true,
                'message' => '✅ Todas las alertas marcadas como leídas'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Error al marcar las alertas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar una alerta
     */
    public function destroy($id)
    {
        try {
            $alerta = Alerta::findOrFail($id);
            $alerta->delete();

            return response()->json([
                'success' => true,
                'message' => '✅ Alerta eliminada exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Error al eliminar la alerta: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar alerta
     */
    public function update(Request $request, $id)
    {
        $alerta = Alerta::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'mensaje' => 'required|string|max:500',
            'tipo' => 'required|in:recordatorio,vencimiento,alerta',
            'prioridad' => 'required|in:baja,media,alta,critica',
            'fecha_limite' => 'nullable|date',
            'observaciones' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $alerta->update($request->all());

            return response()->json([
                'success' => true,
                'message' => '✅ Alerta actualizada exitosamente',
                'alerta' => $alerta->load(['proveedor', 'creador'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Error al actualizar la alerta: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar alertas automáticamente (monitoreo)
     */
    public function actualizarAlertas()
    {
        try {
            $cambios = 0;
            
            // Obtener proveedores con monitoreo activo
            $proveedores = Proveedor::where('monitoreo_activo', true)
                ->whereNotNull('fecha_facturacion')
                ->get();
            
            foreach ($proveedores as $proveedor) {
                // Verificar si hay facturas recientes
                $ultimaFactura = $proveedor->facturas()
                    ->where('fecha_factura', '>=', $proveedor->fecha_facturacion)
                    ->latest('fecha_factura')
                    ->first();
                
                $diasSinFacturar = $ultimaFactura 
                    ? now()->diffInDays($ultimaFactura->fecha_factura)
                    : now()->diffInDays($proveedor->fecha_facturacion);
                
                // Crear alerta si se supera el límite de días
                if ($diasSinFacturar > $proveedor->dias_alertar) {
                    // Verificar si ya existe una alerta similar
                    $alertaExistente = Alerta::where('proveedor_id', $proveedor->id)
                        ->where('tipo', 'vencimiento')
                        ->where('activa', true)
                        ->where('leida', false)
                        ->first();
                    
                    if (!$alertaExistente) {
                        Alerta::create([
                            'proveedor_id' => $proveedor->id,
                            'mensaje' => "El proveedor {$proveedor->proveedor} tiene {$diasSinFacturar} días sin facturar. Última factura: " . ($ultimaFactura ? $ultimaFactura->fecha_factura->format('d/m/Y') : 'Ninguna'),
                            'tipo' => 'vencimiento',
                            'prioridad' => $diasSinFacturar > 30 ? 'critica' : ($diasSinFacturar > 15 ? 'alta' : 'media'),
                            'fecha_limite' => now()->addDays($proveedor->dias_alertar),
                            'observaciones' => "Días sin facturar: {$diasSinFacturar}. Límite establecido: {$proveedor->dias_alertar} días.",
                            'creado_por' => 1, // Usuario sistema
                            'leida' => false,
                            'activa' => true
                        ]);
                        $cambios++;
                    }
                }
            }
            
            return response()->json([
                'success' => true,
                'cambios' => $cambios,
                'message' => "✅ Alertas actualizadas. {$cambios} nuevas alertas creadas."
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Error al actualizar alertas: ' . $e->getMessage()
            ], 500);
        }
    }
}