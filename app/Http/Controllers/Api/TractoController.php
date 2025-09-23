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

        return response()->json($items);
    }

    public function show(string $id)  { return response()->json(Tracto::findOrFail($id)); }
    public function store(StoreTractoRequest $req) { $tracto = Tracto::create($req->validated()); return response()->json($tracto,201); }
    public function update(string $id, UpdateTractoRequest $req) { $tracto=Tracto::findOrFail($id); $tracto->update($req->validated()); return response()->json($tracto); }
    public function destroy(string $id) { Tracto::findOrFail($id)->delete(); return response()->json([],204); }
}