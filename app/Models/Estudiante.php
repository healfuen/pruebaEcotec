<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = [
        'codigo',
        'nombre',
        'apellido',
        'email',
    ];

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class);
    }

    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'inscripciones');
    }
}
