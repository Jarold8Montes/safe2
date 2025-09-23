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
            // preferencia por text index
            $q->whereRaw(['$text' => ['$search' => $req->q]]);
        }

        $orderBy = $req->get('order_by','created_at');
        $order   = $req->get('order','desc');

        $items = $q->orderBy($orderBy,$order)
                   ->paginate(min(100,(int)$req->get('per_page',10)));

        return response()->json($items);
    }

    public function show(string $id)  { return response()->json(Operador::findOrFail($id)); }
    public function store(StoreOperadorRequest $req) { $op = Operador::create($req->validated()); return response()->json($op,201); }
    public function update(string $id, UpdateOperadorRequest $req) { $op=Operador::findOrFail($id); $op->update($req->validated()); return response()->json($op); }
    public function destroy(string $id) { Operador::findOrFail($id)->delete(); return response()->json([],204); }

    public function search(Request $req)
    {
        $q = trim($req->get('q',''));
        if ($q==='') return response()->json([]);

        // resultado reducido
        $res = Operador::whereRaw(['$text'=>['$search'=>$q]])
            ->limit(20)
            ->get(['_id','id_operador','nombre']);

        return response()->json($res);
    }
}