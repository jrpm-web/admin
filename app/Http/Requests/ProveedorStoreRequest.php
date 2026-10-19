<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProveedorStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Obtener el ID de la ruta solo si existe (para update)
        $proveedorId = $this->route('proveedor');

        return [
            'categoria_id' => [
                'required',
                'integer',
                'exists:categorias,id'
            ],
            'proveedor' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s\.\-_]+$/'
            ],
            'asesor' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\.\-_]+$/'
            ],
            'telefono_contacto' => [
                'nullable',
                'string',
                'max:50',
                'regex:/^[0-9\+\-\s\(\)]+$/'
            ],
            'ciudad' => ['nullable', 'string', 'max:100'],
            'correo' => [
                'required',
                'email',
                'max:255',
                // ✅ Solo aplicar unique si hay ID, y si no, unique sin ignore
                Rule::unique('proveedores', 'correo')
                    ->whereNull('deleted_at')
                    ->ignore($proveedorId ?? null), // <- Ahora es seguro
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
            ],
            'descripcion_corta' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\.\,\-\_\;\:\?\!\(\)\"]+$/'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'categoria_id.required' => 'Debe seleccionar una categoría',
            'categoria_id.integer' => 'El formato de la categoría no es válido',
            'categoria_id.exists' => 'La categoría seleccionada no existe en el sistema',
            
            'proveedor.required' => 'El nombre del proveedor es obligatorio',
            'proveedor.max' => 'El nombre del proveedor no puede exceder los 100 caracteres',
            'proveedor.regex' => 'El nombre del proveedor solo puede contener letras, números, espacios y guiones',
            
            'asesor.required' => 'El nombre del asesor es obligatorio',
            'asesor.max' => 'El nombre del asesor no puede exceder los 100 caracteres',
            'asesor.regex' => 'El nombre del asesor solo puede contener letras, espacios y guiones',
            
            'telefono_contacto.regex' => 'El teléfono solo puede contener números, +, -, espacios y paréntesis',
            
            'correo.required' => 'El correo electrónico es obligatorio',
            'correo.email' => 'Ingrese un correo electrónico válido',
            'correo.unique' => 'Este correo electrónico ya está registrado',
            'correo.max' => 'El correo electrónico no puede exceder los 255 caracteres',
            'correo.regex' => 'Ingrese un correo electrónico válido',
            
            'descripcion_corta.max' => 'La descripción no puede exceder los 255 caracteres',
            'descripcion_corta.regex' => 'La descripción contiene caracteres no permitidos',
        ];
    }

    /**
     * Preparar los datos para validación
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'proveedor' => trim($this->proveedor ?? ''),
            'asesor' => trim($this->asesor ?? ''),
            'correo' => strtolower(trim($this->correo ?? '')),
            'descripcion_corta' => trim($this->descripcion_corta ?? ''),
        ]);
    }

    /**
     * Sanitizar los datos después de la validación
     */
    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated($key, $default);
        
        if (is_array($validated)) {
            foreach ($validated as $k => $v) {
                if (is_string($v)) {
                    $validated[$k] = htmlspecialchars($v, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                }
            }
        }
        
        return $validated;
    }

    /**
     * 🔥 NUEVO: Manejar validación fallida para AJAX
     */
    protected function failedValidation(Validator $validator)
    {
        // Si la solicitud espera JSON, devolver errores en JSON
        if ($this->expectsJson()) {
            throw new HttpResponseException(
                response()->json(['errors' => $validator->errors()], 422)
            );
        }

        // Si no, usar el comportamiento normal (redirección)
        parent::failedValidation($validator);
    }
}