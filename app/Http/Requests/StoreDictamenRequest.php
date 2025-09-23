<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDictamenRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'viaje_id'    => 'nullable|string',
            'operador_id' => 'required|string',
            'tracto_id'   => 'nullable|string',
            'apto'        => 'required|boolean',
            'bmp'         => 'required|integer|min:20|max:220',
            'fecha'       => 'required|date',
        ];
    }
}