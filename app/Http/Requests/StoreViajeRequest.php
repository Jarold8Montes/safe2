<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreViajeRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'id_viaje'    => 'required|string|unique:viajes,id_viaje',
            'origen'      => 'required|string|max:255',
            'destino'     => 'required|string|max:255',
            'fecha'       => 'required|date',
            'operador_id' => 'required|string',
            'tracto_id'   => 'required|string',
            'estado'      => 'required|string|in:pendiente,en_curso,completado,cancelado',
        ];
    }
}