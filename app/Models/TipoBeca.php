<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoBeca extends Model
{
    protected $fillable = ['propiedade_id'];

    // una beca tiene muchos estudiantes
    public function estudiante()
    {
        return $this->hasMany(Estudiante::class);
    }

    // una beca pertenece a una propiedad
    public function propiedade()
    {
        return $this->belongsTo(Propiedade::class);
    }
}
