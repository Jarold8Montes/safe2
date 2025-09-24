<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tracto;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTractoRequest;
use App\Http\Requests\UpdateTractoRequest;

class TractoController extends Controller
{
    public function index(Request $req)
    {
        $q = Tracto::query();

        if ($req->filled('placas')) $q->where('placas', 'like', '%'.$req->placas.'%');
        if ($req->filled('marca'))  $q->where('marca', 'like', '%'.$req->marca.'%');
        if ($req->filled('modelo')) $q->where('modelo', 'like', '%'.$req->modelo.'%');
        if ($req->filled('activo')) $q->where('activo', filter_var($req->activo, FILTER_VALIDATE_BOOLEAN));

        $orderBy = $req->get('order_by','created_at');
        $order   = $req->get('order','desc');

        $items = $q->orderBy($orderBy,$order)
                   ->paginate(min(100,(int)$req->get('per_page',10)));

        return $this->sendResponse($items, 'Tractos listados correctamente', 200);
    }

    public function show(string $id)
    {
        // Check if the provided ID is a valid MongoDB ObjectId
        if (preg_match('/^[0-9a-f]{24}$/i', $id)) {
            $tracto = Tracto::find($id);
        } else {
            // Assume it's an id_tracto
            $tracto = Tracto::where('id_tracto', $id)->first();
        }

        if (!$tracto) {
            return $this->sendError('Tracto no encontrado', [], 404);
        }

        return $this->sendResponse($tracto, 'Tracto encontrado', 200);
    }
    public function store(StoreTractoRequest $req)
    {
        $tracto = Tracto::create($req->validated());
        return $this->sendResponse($tracto, 'Tracto creado correctamente', 201);
    }
    public function update(string $id, UpdateTractoRequest $req)
    {
        // Find the tracto by either MongoDB _id or id_tracto
        if (preg_match('/^[0-9a-f]{24}$/i', $id)) {
            $tracto = Tracto::find($id);
        } else {
            $tracto = Tracto::where('id_tracto', $id)->first();
        }

        if (!$tracto) {
            return $this->sendError('Tracto no encontrado', [], 404);
        }

        $tracto->update($req->validated());
        return $this->sendResponse($tracto, 'Tracto actualizado correctamente', 200);
    }
    public function destroy(string $id)
    {
        // Find the tracto by either MongoDB _id or id_tracto
        if (preg_match('/^[0-9a-f]{24}$/i', $id)) {
            $tracto = Tracto::find($id);
        } else {
            $tracto = Tracto::where('id_tracto', $id)->first();
        }

        if (!$tracto) {
            return $this->sendError('Tracto no encontrado', [], 404);
        }

        $tracto->delete();
        return $this->sendResponse([], 'Tracto eliminado correctamente', 204);
    }
}