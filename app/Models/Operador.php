<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\HasMany;
use Carbon\Carbon;

class Operador extends Model
{
    protected $connection = 'mongodb';
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

    /**
     * Calcula y devuelve el rango de BPM recomendado.
     *
     * @return array
     */
    public function getBpmRangeAttribute(): array
    {
        $score = $this->calculateRiskScore();

        if ($score < config('bpm_rules.score_thresholds.wide_range')) {
            return config('bpm_rules.ranges.wide');
        }

        return config('bpm_rules.ranges.optimal');
    }

    /**
     * Calcula el puntaje de riesgo del operador.
     *
     * @return float
     */
    public function calculateRiskScore(): float
    {
        $score = 0.0;

        // Puntuación por edad
        if ($this->fecha_nacimiento) {
            $age = Carbon::parse($this->fecha_nacimiento)->age;
            if ($age > config('bpm_rules.age_threshold')) {
                $score += config('bpm_rules.age_score');
            }
        }

        // Puntuación por factores de riesgo
        $riskFactors = $this->factores_riesgo ?? [];
        if (in_array('ninguno', $riskFactors)) {
            return $score;
        }

        $factorWeights = config('bpm_rules.risk_factors');
        foreach ($riskFactors as $factor) {
            if (isset($factorWeights[$factor])) {
                $score += $factorWeights[$factor];
            }
        }

        return $score;
    }
}