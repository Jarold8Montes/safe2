<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTractoRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'id_tracto' => 'required|string|unique:tractos,id_tracto',
            'placas'    => 'required|string|max:20',
            'marca'     => 'nullable|string|max:255',
            'modelo'    => 'nullable|string|max:255',
            'activo'    => 'boolean',
        ];
    }
}