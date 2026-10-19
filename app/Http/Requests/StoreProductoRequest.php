<?php

namespace App\Http\Requests;

use App\Models\EstatusProducto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Aquí puedes agregar lógica de autorización
        // Ejemplo: return auth()->user()->can('create productos');
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            // Información principal
            'nombre' => ['required', 'string', 'max:100'],
            'codigo' => ['required', 'string', 'max:50', 'unique:productos,codigo'],
            'descripcion' => ['nullable', 'string', 'max:500'],
            
            // Precios y stock
            'precio_compra' => ['nullable', 'numeric', 'min:0', 'regex:/^\d+(\.\d{1,2})?$/'],
            'precio_venta' => ['nullable', 'numeric', 'min:0', 'gte:precio_compra', 'regex:/^\d+(\.\d{1,2})?$/'],
            'stock' => ['required', 'integer', 'min:0'],
            'stock_minimo' => ['required', 'integer', 'min:0'],
            
            // Relaciones
            'categoria_id' => ['required', 'integer', 'exists:categorias,id'],
            'proveedor_id' => ['required', 'integer', 'exists:proveedores,id'],
            
            // Información adicional
            'unidad_medida' => ['nullable', 'string', 'max:20'],
            'ubicacion' => ['nullable', 'string', 'max:255'],
            'activo' => ['nullable', 'boolean'],
            
            // Estatus y asignación
            'estatus' => ['required', Rule::in(array_keys(EstatusProducto::ESTATUSES))],
            'observaciones' => ['nullable', 'string', 'max:500'],
            
            // Campos de asignación (condicionales)
            'asignado_a' => ['nullable', 'string', 'max:100'],
            'email_usuario' => ['nullable', 'email', 'max:255'],
            'telefono_usuario' => ['nullable', 'string', 'max:50'],
            'fecha_asignacion' => ['nullable', 'date', 'date_format:Y-m-d'],
            'fecha_devolucion' => ['nullable', 'date', 'date_format:Y-m-d', 'after_or_equal:fecha_asignacion'],
            'notas_asignacion' => ['nullable', 'string', 'max:500'],
        ];

        // Si el estatus es "asignado" o "prestamo", ciertos campos son obligatorios
        if (in_array($this->estatus, ['asignado', 'prestamo'])) {
            $rules['asignado_a'] = ['required', 'string', 'max:100'];
            $rules['email_usuario'] = ['required', 'email', 'max:255'];
            $rules['fecha_asignacion'] = ['required', 'date', 'date_format:Y-m-d'];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            // Información principal
            'nombre.required' => 'El nombre del producto es obligatorio',
            'nombre.string' => 'El nombre debe ser un texto válido',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres',
            
            'codigo.required' => 'El código del producto es obligatorio',
            'codigo.string' => 'El código debe ser un texto válido',
            'codigo.max' => 'El código no puede tener más de 50 caracteres',
            'codigo.unique' => 'Este código ya está registrado en el sistema',
            
            'descripcion.string' => 'La descripción debe ser un texto válido',
            'descripcion.max' => 'La descripción no puede tener más de 500 caracteres',
            
            // Precios y stock
            
            'precio_compra.numeric' => 'El precio de compra debe ser un número válido',
            'precio_compra.min' => 'El precio de compra debe ser mayor o igual a 0',
            'precio_compra.regex' => 'El precio de compra solo puede tener 2 decimales',
            
            
            'precio_venta.numeric' => 'El precio de venta debe ser un número válido',
            'precio_venta.min' => 'El precio de venta debe ser mayor o igual a 0',
            'precio_venta.gte' => 'El precio de venta debe ser mayor o igual al precio de compra',
            'precio_venta.regex' => 'El precio de venta solo puede tener 2 decimales',
            
            'stock.required' => 'El stock es obligatorio',
            'stock.integer' => 'El stock debe ser un número entero',
            'stock.min' => 'El stock debe ser mayor o igual a 0',
            
            'stock_minimo.required' => 'El stock mínimo es obligatorio',
            'stock_minimo.integer' => 'El stock mínimo debe ser un número entero',
            'stock_minimo.min' => 'El stock mínimo debe ser mayor o igual a 0',
            
            // Relaciones
            'categoria_id.required' => 'Debes seleccionar una categoría',
            'categoria_id.integer' => 'La categoría seleccionada no es válida',
            'categoria_id.exists' => 'La categoría seleccionada no existe en el sistema',
            
            'proveedor_id.required' => 'Debes seleccionar un proveedor',
            'proveedor_id.integer' => 'El proveedor seleccionado no es válido',
            'proveedor_id.exists' => 'El proveedor seleccionado no existe en el sistema',
            
            // Información adicional
            'unidad_medida.string' => 'La unidad de medida debe ser un texto válido',
            'unidad_medida.max' => 'La unidad de medida no puede tener más de 20 caracteres',
            
            'ubicacion.string' => 'La ubicación debe ser un texto válido',
            'ubicacion.max' => 'La ubicación no puede tener más de 255 caracteres',
            
            'activo.boolean' => 'El estado activo debe ser verdadero o falso',
            
            // Estatus
            'estatus.required' => 'Debes seleccionar un estatus',
            'estatus.in' => 'El estatus seleccionado no es válido',
            
            'observaciones.string' => 'Las observaciones deben ser un texto válido',
            'observaciones.max' => 'Las observaciones no pueden tener más de 500 caracteres',
            
            // Asignación
            'asignado_a.required' => 'El campo "Asignado a" es obligatorio cuando el producto está asignado o en préstamo',
            'asignado_a.string' => 'El nombre debe ser un texto válido',
            'asignado_a.max' => 'El nombre no puede tener más de 100 caracteres',
            
            'email_usuario.required' => 'El campo "Correo del Usuario" es obligatorio cuando el producto está asignado o en préstamo',
            'email_usuario.email' => 'Ingresa un correo electrónico válido',
            'email_usuario.max' => 'El correo no puede tener más de 255 caracteres',
            
            'telefono_usuario.string' => 'El teléfono debe ser un texto válido',
            'telefono_usuario.max' => 'El teléfono no puede tener más de 50 caracteres',
            
            'fecha_asignacion.required' => 'La fecha de asignación es obligatoria cuando el producto está asignado o en préstamo',
            'fecha_asignacion.date' => 'La fecha de asignación no es válida',
            'fecha_asignacion.date_format' => 'El formato de la fecha de asignación debe ser YYYY-MM-DD',
            
            'fecha_devolucion.date' => 'La fecha de devolución no es válida',
            'fecha_devolucion.date_format' => 'El formato de la fecha de devolución debe ser YYYY-MM-DD',
            'fecha_devolucion.after_or_equal' => 'La fecha de devolución debe ser igual o posterior a la fecha de asignación',
            
            'notas_asignacion.string' => 'Las notas de asignación deben ser un texto válido',
            'notas_asignacion.max' => 'Las notas de asignación no pueden tener más de 500 caracteres',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Limpiar y formatear datos antes de la validación
        $this->merge([
            'nombre' => trim($this->nombre),
            'codigo' => strtoupper(trim($this->codigo)),
            'email_usuario' => $this->email_usuario ? strtolower(trim($this->email_usuario)) : null,
            'activo' => $this->has('activo') ? true : false,
        ]);
    }
}