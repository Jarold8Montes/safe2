<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Viaje;
use Illuminate\Http\Request;
use App\Http\Requests\StoreViajeRequest;
use App\Http\Requests\UpdateViajeRequest;

class ViajeController extends Controller
{
    public function index(Request $req)
    {
        $q = Viaje::query();

        if ($req->filled('origen'))      $q->where('origen', 'like', '%'.$req->origen.'%');
        if ($req->filled('destino'))     $q->where('destino', 'like', '%'.$req->destino.'%');
        if ($req->filled('operador_id')) $q->where('operador_id', $req->operador_id);
        if ($req->filled('tracto_id'))   $q->where('tracto_id', $req->tracto_id);
        if ($req->filled('estado'))      $q->where('estado', $req->estado);
        if ($req->filled('from'))        $q->where('fecha', '>=', $req->from);
        if ($req->filled('to'))          $q->where('fecha', '<=', $req->to);

        $orderBy = $req->get('order_by','fecha');
        $order   = $req->get('order','desc');

        $items = $q->orderBy($orderBy,$order)
                   ->paginate(min(100,(int)$req->get('per_page',10)));

        return $this->sendResponse($items, 'Viajes listados correctamente', 200);
    }

    public function show(string $id)
    {
        // Check if the provided ID is a valid MongoDB ObjectId
        if (preg_match('/^[0-9a-f]{24}$/i', $id)) {
            $viaje = Viaje::find($id);
        } else {
            // Assume it's an id_viaje
            $viaje = Viaje::where('id_viaje', $id)->first();
        }

        if (!$viaje) {
            return $this->sendError('Viaje no encontrado', [], 404);
        }

        return $this->sendResponse($viaje, 'Viaje encontrado', 200);
    }
    public function store(StoreViajeRequest $req)
    {
        $viaje = Viaje::create($req->validated());
        return $this->sendResponse($viaje, 'Viaje creado correctamente', 201);
    }
    public function update(string $id, UpdateViajeRequest $req)
    {
        // Find the viaje by either MongoDB _id or id_viaje
        if (preg_match('/^[0-9a-f]{24}$/i', $id)) {
            $viaje = Viaje::find($id);
        } else {
            $viaje = Viaje::where('id_viaje', $id)->first();
        }

        if (!$viaje) {
            return $this->sendError('Viaje no encontrado', [], 404);
        }

        $viaje->update($req->validated());
        return $this->sendResponse($viaje, 'Viaje actualizado correctamente', 200);
    }
    public function destroy(string $id)
    {
        // Find the viaje by either MongoDB _id or id_viaje
        if (preg_match('/^[0-9a-f]{24}$/i', $id)) {
            $viaje = Viaje::find($id);
        } else {
            $viaje = Viaje::where('id_viaje', $id)->first();
        }

        if (!$viaje) {
            return $this->sendError('Viaje no encontrado', [], 404);
        }

        $viaje->delete();
        return $this->sendResponse([], 'Viaje eliminado correctamente', 204);
    }

    public function cambiarEstado(string $id, Request $req)
    {
        $viaje = Viaje::findOrFail($id);
        $req->validate([
            'estado' => 'required|string|in:pendiente,en_curso,completado,cancelado',
        ]);
        $viaje->estado = $req->estado;
        $viaje->save();
        return $this->sendResponse($viaje, 'Estado del viaje actualizado correctamente', 200);
    }
}