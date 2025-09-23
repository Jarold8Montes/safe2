<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateViajeRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'id_viaje'    => 'sometimes|string|unique:viajes,id_viaje,'.$this->route('id'),
            'origen'      => 'sometimes|string|max:255',
            'destino'     => 'sometimes|string|max:255',
            'fecha'       => 'sometimes|date',
            'operador_id' => 'sometimes|string',
            'tracto_id'   => 'sometimes|string',
            'estado'      => 'sometimes|string|in:pendiente,en_curso,completado,cancelado',
        ];
    }
}