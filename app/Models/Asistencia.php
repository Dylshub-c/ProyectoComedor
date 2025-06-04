<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha',
        'tipo_asistencia',
        'observaciones',
        'estado'
    ];

    public function listadoAsistencias()
    {
        return $this->hasMany(ListadoAsistencia::class, 'asistencia_id');
    }
}
    