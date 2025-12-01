<?php
namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\BelongsTo;

class Viaje extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'viajes';
    protected $fillable = [
        'id_viaje','origen','destino','fecha','operador_id','tracto_id','estado'
    ];
    protected $casts = ['fecha'=>'datetime'];

    public function operador(): BelongsTo { return $this->belongsTo(Operador::class); }
    public function tracto(): BelongsTo { return $this->belongsTo(Tracto::class); }
}