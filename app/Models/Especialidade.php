<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Especialidade extends Model
{
    use HasFactory;

    protected $fillable = ['propiedade_id'];


    // una especialidad tiene muchos estudiantes
    public function estudiante(){
        return $this->hasMany(Estudiante::class);
    }

    // una especialidad pertenece a una propiedad
    public function propiedade(){
        return $this->belongsTo(Propiedade::class);
    }

}
