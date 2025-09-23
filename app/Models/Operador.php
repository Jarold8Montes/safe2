<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\HasMany;

class Operador extends Model
{
    protected $collection = 'operadores';
    protected $fillable = [
        'id_operador','nombre','foto_url','fecha_nacimiento','telefono','factores_riesgo','activo'
    ];
    protected $casts = [
        'factores_riesgo' => 'array',
        'activo' => 'boolean',
        'fecha_nacimiento' => 'datetime',
    ];

    public function dictamenes(): HasMany { return $this->hasMany(Dictamen::class); }
}