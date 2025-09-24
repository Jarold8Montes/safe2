<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\HasMany;
use Carbon\Carbon;

class Operador extends Model
{
    protected $collection = 'operadores';
    protected $fillable = [
        'id_operador','nombre','foto_url','fecha_nacimiento','telefono','factores_riesgo','activo','genero'
    ];
    protected $casts = [
        'factores_riesgo' => 'array',
        'activo' => 'boolean',
        'fecha_nacimiento' => 'datetime',
    ];

    protected $appends = ['bpm_range'];

    public function dictamenes(): HasMany { return $this->hasMany(Dictamen::class); }

    public function getBpmRangeAttribute()
    {
        $score = 0;

        // Calculate age
        if ($this->fecha_nacimiento) {
            $age = Carbon::parse($this->fecha_nacimiento)->age;
            if ($age > 50) {
                $score += 1;
            }
        }

        // Risk factors
        $riskFactors = $this->factores_riesgo ?? [];
        if (!in_array('ninguno', $riskFactors)) {
            if (in_array('Hipertensión arterial', $riskFactors)) $score += 2;
            if (in_array('Diabetes o prediabetes', $riskFactors)) $score += 2;
            if (in_array('Sistema inmunológico debilitado', $riskFactors)) $score += 2;
            if (in_array('Obesidad o sobrepeso', $riskFactors)) $score += 1.5;
            if (in_array('Niveles elevados de colesterol o triglicéridos', $riskFactors)) $score += 1;
            if (in_array('Trastornos hormonales', $riskFactors)) $score += 1;
        }

        $min = 60; // Default to optimal range
        $max = 100;

        if ($score < 3) {
            $min = 50;
            $max = 110;
        }

        return ['min' => $min, 'max' => $max];
    }
}