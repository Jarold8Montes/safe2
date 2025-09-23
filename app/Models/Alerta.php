<?php
namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Alerta extends Model
{
    protected $collection = 'alertas';
    protected $fillable = [
        'tipo','mensaje','operador_id','viaje_id','dictamen_id','leida','fecha'
    ];
    protected $casts = ['leida'=>'boolean','fecha'=>'datetime'];
}