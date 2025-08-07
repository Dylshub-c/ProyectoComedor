<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListadoAsistencia extends Model
{
    use HasFactory;

    protected $table = 'listado_asistencias';

    protected $fillable = [
        'observaciones',
        'estudiante_id',
        'asistencia_id'
    ];

    public function asistencia()
    {
        return $this->belongsTo(Asistencia::class);
    }

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }
}
