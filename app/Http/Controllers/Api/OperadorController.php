<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Operador;
use Illuminate\Http\Request;
use App\Http\Requests\StoreOperadorRequest;
use App\Http\Requests\UpdateOperadorRequest;

class OperadorController extends Controller
{
    public function index(Request $req)
    {
        $q = Operador::query();

        if ($req->filled('q')) {
            $q->whereRaw(['$text' => ['$search' => $req->q]]);
        }

        $orderBy = $req->get('order_by','created_at');
        $order   = $req->get('order','desc');

        $items = $q->orderBy($orderBy,$order)
                   ->paginate(min(100,(int)$req->get('per_page',10)));

        return $this->sendResponse($items, 'Operadores listados correctamente', 200);
    }

    public function show(string $id)
    {
        $operador = Operador::findOrFail($id);
        return $this->sendResponse($operador, 'Operador encontrado', 200);
    }
    public function store(StoreOperadorRequest $req)
    {
        $op = Operador::create($req->validated());
        return $this->sendResponse($op, 'Operador creado correctamente', 201);
    }
    public function update(string $id, UpdateOperadorRequest $req)
    {
        $op=Operador::findOrFail($id);
        $op->update($req->validated());
        return $this->sendResponse($op, 'Operador actualizado correctamente', 200);
    }
    public function destroy(string $id)
    {
        Operador::findOrFail($id)->delete();
        return $this->sendResponse([], 'Operador eliminado correctamente', 204);
    }

    public function search(Request $req)
    {
        $q = trim($req->get('q',''));
        if ($q==='') return $this->sendResponse([], 'No hay coincidencias', 200); // Or handle as an error if empty query is an error

        // resultado reducido
        $res = Operador::where('nombre', 'regexp', "/$q/i")
            ->limit(20)
            ->get(['_id','id_operador','nombre']);

        if ($res->isEmpty()) {
            return $this->sendError('No hay coincidencias', [], 404);
        }

        return $this->sendResponse($res, 'Operadores encontrados', 200);
    }
}