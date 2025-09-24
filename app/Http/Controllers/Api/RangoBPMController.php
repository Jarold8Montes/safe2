<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Operador;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class RangoBPMController extends Controller
{
    public function calculate($id_operador): JsonResponse
    {
        $operador = Operador::where('id_operador', $id_operador)->firstOrFail();

        $score = 0;

        // Calculate age
        if ($operador->fecha_nacimiento) {
            $age = Carbon::parse($operador->fecha_nacimiento)->age;
            if ($age > 50) {
                $score += 1;
            }
        }

        // Risk factors
        $riskFactors = $operador->factores_riesgo ?? [];
        if (!in_array('ninguno', $riskFactors)) {
            if (in_array('Hipertensión arterial', $riskFactors)) $score += 2;
            if (in_array('Diabetes o prediabetes', $riskFactors)) $score += 2;
            if (in_array('Sistema inmunológico debilitado', $riskFactors)) $score += 2;
            if (in_array('Obesidad o sobrepeso', $riskFactors)) $score += 1.5;
            if (in_array('Niveles elevados de colesterol o triglicéridos', $riskFactors)) $score += 1;
            if (in_array('Trastornos hormonales', $riskFactors)) $score += 1;
        }

        $min = 0;
        $max = 0;

        if ($score < 3) {
            $min = 50;
            $max = 110;
        } elseif ($score < 4) {
            $min = 60;
            $max = 100;
        }

        return response()->json([
            'id_operador' => $id_operador,
            'min' => $min,
            'max' => $max,
        ]);
    }
}
