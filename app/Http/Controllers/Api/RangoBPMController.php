<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Operador;
use Illuminate\Http\JsonResponse;

class RangoBPMController extends Controller
{
    public function calculate($id_operador): JsonResponse
    {
        $operador = Operador::where('id_operador', $id_operador)->firstOrFail();
        $bpmRange = $operador->bpm_range;

        return response()->json([
            'id_operador' => $id_operador,
            'min' => $bpmRange['min'],
            'max' => $bpmRange['max'],
        ]);
    }
}
