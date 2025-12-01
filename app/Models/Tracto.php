<?php
namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Tracto extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'tractos';
    protected $fillable = ['id_tracto','placas','marca','modelo','activo','is_engine_on'];
    protected $casts = ['activo'=>'boolean','is_engine_on'=>'boolean'];
    protected $attributes = ['is_engine_on' => false];
}