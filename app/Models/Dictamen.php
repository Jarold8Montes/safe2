<?php
namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\BelongsTo;

class Dictamen extends Model
{
    protected $collection = 'dictamenes';
    protected $fillable = [
        'viaje_id','operador_id','tracto_id','apto','bmp','fecha'
    ];
    protected $casts = ['apto'=>'boolean','bmp'=>'integer','fecha'=>'datetime'];

    public function operador(): BelongsTo { return $this->belongsTo(Operador::class); }
    public function viaje(): BelongsTo { return $this->belongsTo(Viaje::class); }
    public function tracto(): BelongsTo { return $this->belongsTo(Tracto::class); }
}