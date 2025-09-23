<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTractoRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'id_tracto' => 'sometimes|string|unique:tractos,id_tracto,'.$this->route('tracto'),
            'placas'    => 'sometimes|string|max:20',
            'marca'     => 'nullable|string|max:255',
            'modelo'    => 'nullable|string|max:255',
            'activo'    => 'sometimes|boolean',
        ];
    }
}