<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alerta;
use App\Models\Operador;
use App\Models\Viaje;
use App\Models\Dictamen;
use App\Models\User; // Import User model
use Illuminate\Http\Request;

class AlertaController extends Controller
{
    public function index(Request $req)
    {
        $q = Alerta::query();
        $q->where('tipo', 'no_apto'); // Filter for 'no_apto' alerts

        if ($req->filled('leida')) $q->where('leida', filter_var($req->leida, FILTER_VALIDATE_BOOLEAN));
        if ($req->filled('operador_id')) $q->where('operador_id', $req->operador_id);
        if ($req->filled('from')) $q->where('fecha', '>=', $req->from);
        if ($req->filled('to'))   $q->where('fecha', '<=', $req->to);

        $orderBy = $req->get('order_by', 'fecha');
        $order   = $req->get('order', 'desc');

        $page = $q->orderBy($orderBy, $order)
                  ->paginate(min(100, (int)$req->get('per_page', 10)));

        // expandir en el formato que pediste para el móvil
        $enriched = collect($page->items())->map(function ($a) {
            $operador = $a->operador_id ? Operador::find($a->operador_id) : null;
            $viaje    = $a->viaje_id ? Viaje::find($a->viaje_id) : null;
            $dictamen = $a->dictamen_id ? Dictamen::find($a->dictamen_id) : null;
            $supervisor = null; // Initialize supervisor

            // If dictamen exists and has an operator, try to find the supervisor
            if ($dictamen && $dictamen->operador_id) {
                // Assuming supervisor is linked to the operator or dictamen in some way
                // For now, let's assume a generic supervisor or fetch based on some logic
                // For this example, I'll just fetch the first supervisor user
                $supervisor = User::where('rol', 'supervisor')->first();
            }


            return [
                'id' => (string)$a->_id,
                'viaje' => $viaje ? [
                    'id_viaje' => $viaje->id_viaje ?? null,
                    'origen'   => $viaje->origen ?? null,
                    'destino'  => $viaje->destino ?? null,
                    'fecha'    => $viaje->fecha ?? null,
                ] : null,
                'operador' => $operador ? [
                    'id_operador'    => $operador->id_operador ?? null,
                    'nombre'         => $operador->nombre ?? null,
                    'foto'           => $operador->foto_url ?? null,
                    'fecha_nacimiento' => $operador->fecha_nacimiento ?? null, // Changed from 'edad'
                    'telefono'       => $operador->telefono ?? null,
                    'factores_riesgo'=> $operador->factores_riesgo ?? [],
                ] : null,
                'tracto' => $viaje && $viaje->tracto_id ? \App\Models\Tracto::find($viaje->tracto_id) : null, // Fetch Tracto info
                'dictamen' => $dictamen ? [
                    'apto' => (int) $dictamen->apto, // Cast boolean to integer (0 or 1)
                    'bmp'  => $dictamen->bmp,
                ] : null,
                'supervisor' => $supervisor ? [
                    'id_supervisor' => $supervisor->id_supervisor ?? null,
                    'nombre' => $supervisor->nombre ?? null,
                    'email' => $supervisor->email ?? null,
                    // 'password' => '******', // Never expose password
                    'rol' => $supervisor->rol ?? null,
                ] : null,
                'fecha' => $a->fecha,
            ];
        });

        return response()->json([
            'data' => $enriched,
            'page' => $page->currentPage(),
            'per_page' => $page->perPage(),
            'total' => $page->total()
        ]);
    }

    public function marcarLeida(string $id)
    {
        $a = Alerta::findOrFail($id);
        $a->leida = true;
        $a->save();
        return response()->json(['ok'=>true]);
    }
}