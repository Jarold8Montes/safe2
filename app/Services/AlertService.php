<?php
// app/Services/AlertService.php
namespace App\Services;

use App\Models\Alerta;

class AlertService
{
    public function maybeCreateFromDictamen(array $ctx): ?Alerta
    {
        $low  = config('devices.bmp_low', 40);
        $high = config('devices.bmp_high', 120);

        $tipo = null;
        $mensaje = null;

        if ($ctx['apto'] === false) {
            $tipo = 'no_apto';
            $mensaje = 'Operador no apto antes/durante el viaje';
        } elseif ($ctx['bmp'] < $low || $ctx['bmp'] > $high) {
            $tipo = 'bmp_fuera_rango';
            $mensaje = "BMP fuera de rango ($low-$high)";
        }

        if (!$tipo) return null;

        return Alerta::create([
            'tipo' => $tipo,
            'mensaje' => $mensaje,
            'operador_id' => $ctx['operador_id'] ?? null,
            'viaje_id' => $ctx['viaje_id'] ?? null,
            'dictamen_id' => $ctx['dictamen_id'] ?? null,
            'leida' => false,
            'fecha' => now(),
        ]);
    }
}