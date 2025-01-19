<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PeriodoAcademico extends Model
{
    use HasFactory, softDeletes;

    protected $table = 'periodos_academicos';

    protected $fillable = [
        'nombre',
        'fecha_inicio',
        'fecha_fin',
    ];
}
