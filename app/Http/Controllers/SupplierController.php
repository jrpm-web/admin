<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Http\Requests\SupplierRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SupplierController extends Controller
{
    public function index(): JsonResponse
    {
        $suppliers = Supplier::withCount('invoices')
            ->orderBy('name')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $suppliers
        ]);
    }

    public function store(SupplierRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['code'] = $this->generateSupplierCode();
        
        $supplier = Supplier::create($validated);
        
        return response()->json([
            'success' => true,
            'data' => $supplier,
            'message' => 'Proveedor creado exitosamente'
        ], 201);
    }

    public function update(SupplierRequest $request, $id): JsonResponse
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->update($request->validated());
        
        return response()->json([
            'success' => true,
            'data' => $supplier,
            'message' => 'Proveedor actualizado'
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $supplier = Supplier::findOrFail($id);
        
        // Verificar si tiene facturas asociadas
        if ($supplier->invoices()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar el proveedor porque tiene facturas asociadas'
            ], 422);
        }
        
        $supplier->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Proveedor eliminado'
        ]);
    }
    
    private function generateSupplierCode(): string
    {
        $lastSupplier = Supplier::orderBy('id', 'desc')->first();
        $nextNumber = $lastSupplier ? intval(substr($lastSupplier->code, 3)) + 1 : 1;
        return 'PRO-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}