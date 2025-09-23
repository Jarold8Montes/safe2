<?php
namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Tracto extends Model
{
    protected $collection = 'tractos';
    protected $fillable = ['id_tracto','placas','marca','modelo','activo'];
    protected $casts = ['activo'=>'boolean'];
}