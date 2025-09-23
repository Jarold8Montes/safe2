<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDictamenRequest;
use App\Models\Dictamen;
use App\Models\Operador;
use App\Models\Viaje;
use App\Services\AlertService;
use Illuminate\Http\Request;

class DictamenController extends Controller
{
    public function __construct(private AlertService $alerts) {}

    public function index(Request $req)
    {
        $q = Dictamen::query();

        if ($req->filled('operador_id')) $q->where('operador_id', $req->operador_id);
        if ($req->filled('viaje_id'))    $q->where('viaje_id', $req->viaje_id);
        if ($req->filled('apto'))        $q->where('apto', filter_var($req->apto, FILTER_VALIDATE_BOOLEAN));
        if ($req->filled('from'))        $q->where('fecha', '>=', $req->from);
        if ($req->filled('to'))          $q->where('fecha', '<=', $req->to);

        $orderBy = $req->get('order_by', 'fecha');
        $order   = $req->get('order', 'desc');

        $dicts = $q->orderBy($orderBy, $order)
                   ->paginate(min(100, (int)$req->get('per_page', 10)));

        return response()->json($dicts);
    }

    public function show(string $id)
    {
        $d = Dictamen::findOrFail($id);
        return response()->json($d);
    }

    public function store(StoreDictamenRequest $req)
    {
        // (Opcional) validar que operador/viaje existan
        if (!Operador::find($req->operador_id)) {
            return response()->json(['error'=>['code'=>'NOT_FOUND','message'=>'operador_id inválido']], 422);
        }
        if ($req->filled('viaje_id') && !Viaje::find($req->viaje_id)) {
            return response()->json(['error'=>['code'=>'NOT_FOUND','message'=>'viaje_id inválido']], 422);
        }

        $d = Dictamen::create($req->validated());

        // crear alerta si aplica
        $alerta = $this->alerts->maybeCreateFromDictamen([
            ...$d->toArray(),
            'dictamen_id' => (string)$d->_id,
        ]);

        return response()->json(['dictamen'=>$d, 'alerta'=>$alerta], 201);
    }

    public function historialPorOperador(string $id, Request $req)
    {
        $q = Dictamen::where('operador_id', $id);
        if ($req->filled('from')) $q->where('fecha', '>=', $req->from);
        if ($req->filled('to'))   $q->where('fecha', '<=', $req->to);

        $orderBy = $req->get('order_by', 'fecha');
        $order   = $req->get('order', 'desc');

        $page = $q->orderBy($orderBy, $order)
                  ->paginate(min(100, (int)$req->get('per_page', 10)));

        // mapea al formato compacto solicitado
        $data = collect($page->items())->map(fn($d) => [
            'fecha'   => $d->fecha,
            'bmp'     => $d->bmp,
            'apto'    => $d->apto,
            'id_viaje'=> $d->viaje_id ? (Viaje::find($d->viaje_id)->id_viaje ?? null) : null,
        ]);

        return response()->json([
            'data' => $data,
            'page' => $page->currentPage(),
            'per_page' => $page->perPage(),
            'total' => $page->total()
        ]);
    }
}