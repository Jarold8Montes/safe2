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

        return response()->json($items);
    }

    public function show(string $id)  { return response()->json(Viaje::findOrFail($id)); }
    public function store(StoreViajeRequest $req) { $viaje = Viaje::create($req->validated()); return response()->json($viaje,201); }
    public function update(string $id, UpdateViajeRequest $req) { $viaje=Viaje::findOrFail($id); $viaje->update($req->validated()); return response()->json($viaje); }
    public function destroy(string $id) { Viaje::findOrFail($id)->delete(); return response()->json([],204); }

    public function cambiarEstado(string $id, Request $req)
    {
        $viaje = Viaje::findOrFail($id);
        $req->validate([
            'estado' => 'required|string|in:pendiente,en_curso,completado,cancelado',
        ]);
        $viaje->estado = $req->estado;
        $viaje->save();
        return response()->json($viaje);
    }
}