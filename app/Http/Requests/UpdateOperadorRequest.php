<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOperadorRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'id_operador'   => 'sometimes|string|unique:operadores,id_operador,'.$this->route('id'),
            'nombre'        => 'sometimes|string|max:255',
            'foto_url'      => 'nullable|url|max:255',
            'fecha_nacimiento' => 'sometimes|date|before_or_equal:today', // Add validation for date of birth
            'telefono'      => 'nullable|string|max:20',
            'factores_riesgo' => 'nullable|array',
            'activo'        => 'sometimes|boolean',
            'genero'        => 'nullable|string|max:50',
        ];
    }
}