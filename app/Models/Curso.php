<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = [
        'codigo',
        'nombre',
        'docente',
        'aula',
        'dia',
        'hora_inicio',
        'hora_fin',
        'cupo',
        'periodo_id',
    ];
}
