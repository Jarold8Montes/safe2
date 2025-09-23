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
        $tracto = Tracto::findOrFail($id);
        return $this->sendResponse($tracto, 'Tracto encontrado', 200);
    }
    public function store(StoreTractoRequest $req)
    {
        $tracto = Tracto::create($req->validated());
        return $this->sendResponse($tracto, 'Tracto creado correctamente', 201);
    }
    public function update(string $id, UpdateTractoRequest $req)
    {
        $tracto=Tracto::findOrFail($id);
        $tracto->update($req->validated());
        return $this->sendResponse($tracto, 'Tracto actualizado correctamente', 200);
    }
    public function destroy(string $id)
    {
        Tracto::findOrFail($id)->delete();
        return $this->sendResponse([], 'Tracto eliminado correctamente', 204);
    }
}