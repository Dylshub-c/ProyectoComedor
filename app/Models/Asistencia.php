<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;

    protected $table = 'asistencias';

    protected $fillable = [
        'fecha_hora',
        'tipo_asistencia',
        'estado'
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
    ];
    
    public function listadosAsistencia()
    {
        return $this->hasMany(ListadoAsistencia::class);
    }
}
    