<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOperadorRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'id_operador'   => 'required|string|unique:operadores,id_operador',
            'nombre'        => 'required|string|max:255',
            'foto_url'      => 'nullable|url|max:255',
            'fecha_nacimiento' => 'required|date|before_or_equal:today', // Add validation for date of birth
            'telefono'      => 'nullable|string|max:20',
            'factores_riesgo' => 'nullable|array',
            'activo'        => 'boolean',
            'genero'        => 'nullable|string|max:50',
        ];
    }
}