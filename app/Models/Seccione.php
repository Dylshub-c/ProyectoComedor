<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seccione extends Model
{
    protected $fillable = ['propiedade_id'];

    // una seccion tiene muchos estudiantes
    public function estudiante()
    {
        return $this->hasMany(Estudiante::class);
    }

    // una seccion pertenece a una propiedad
    public function propiedade()
    {
        return $this->belongsTo(Propiedade::class);
    }
}
