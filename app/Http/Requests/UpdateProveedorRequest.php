<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProveedorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $proveedorId = $this->route('proveedor'); // o 'id' según tu ruta
        $proveedor = $this->route('proveedor') ? \App\Models\Proveedor::find($proveedorId) : null;

        return [
            
            'proveedor' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s\.\-_]+$/',
            'asesor' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\.\-_]+$/',
            'telefono_contacto' => 'nullable|string|max:50|regex:/^[0-9\+\-\s\(\)]+$/',
            'descripcion_corta' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\.\,\-\_\;\:\?\!\(\)\"]+$/',
            'ciudad' => ['nullable', 'string', 'max:100'],
            'correo' => [
                'required',
                'email',
                'max:255',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                // Solo validar unicidad si el correo cambió
                function ($attribute, $value, $fail) use ($proveedor, $proveedorId) {
                    if (!$proveedor) {
                        return; // Si no hay proveedor, no validar (por seguridad)
                    }
                    
                    // Si el correo no cambió, no validar unicidad
                    if ($value === $proveedor->correo) {
                        return;
                    }
                    
                    // Verificar si existe otro proveedor con ese correo (y no eliminado si usas SoftDeletes)
                    $exists = \App\Models\Proveedor::where('correo', $value)
                        ->where('id', '!=', $proveedorId)
                        ->when(method_exists(\App\Models\Proveedor::class, 'bootSoftDeletes'), function ($query) {
                            return $query->whereNull('deleted_at');
                        })
                        ->exists();
                    
                    if ($exists) {
                        $fail('Este correo electrónico ya está registrado por otro proveedor.');
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'proveedor.required' => 'El nombre del proveedor es obligatorio',
            'proveedor.max' => 'El nombre del proveedor no puede exceder los 100 caracteres',
            'proveedor.regex' => 'El nombre del proveedor solo puede contener letras, números, espacios y guiones',
            'asesor.required' => 'El nombre del asesor es obligatorio',
            'asesor.max' => 'El nombre del asesor no puede exceder los 100 caracteres',
            'asesor.regex' => 'El nombre del asesor solo puede contener letras, espacios y guiones',
            'telefono_contacto.regex' => 'El teléfono solo puede contener números, +, -, espacios y paréntesis',
            'correo.required' => 'El correo electrónico es obligatorio',
            'correo.email' => 'Ingrese un correo electrónico válido',
            'correo.max' => 'El correo electrónico no puede exceder los 255 caracteres',
            'correo.regex' => 'Ingrese un correo electrónico válido',
            'descripcion_corta.max' => 'La descripción no puede exceder los 255 caracteres',
            'descripcion_corta.regex' => 'La descripción contiene caracteres no permitidos',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'proveedor' => trim($this->proveedor),
            'asesor' => trim($this->asesor),
            'correo' => strtolower(trim($this->correo)),
            'descripcion_corta' => trim($this->descripcion_corta),
        ]);
    }
}