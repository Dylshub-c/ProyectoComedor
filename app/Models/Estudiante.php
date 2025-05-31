<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Estudiante extends Model
{

    use HasFactory;
    // Definimos los campos que se pueden llenar masivamente
    protected $fillable = [
        'estado',
        'foto',
        'especialidade_id',
        'persona_id',
        'seccione_id',
        'tipo_beca_id'
    ];


    // un estudiante pertenece a un tipo de beca
    public function tipoBeca()
    {
        return $this->belongsTo(TipoBeca::class);
    }

    // un estudiante pertenece a una seccion
    public function seccione()
    {
        return $this->belongsTo(Seccione::class);
    }

    // un estudiante pertenece a una especialidad
    public function especialidade()
    {
        return $this->belongsTo(Especialidade::class);
    }
    // un estudiante pertenece a una persona
    public function persona(){
        return $this->belongsTo(Persona::class);
    }


}
