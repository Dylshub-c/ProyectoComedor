<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Encargado;
use App\Models\Estudiante;


class Persona extends Model
{

    use HasFactory;

    protected $fillable = [
        'Nombre',
        'PrimerApellido',
        'SegundoApellido',
        'Cedula',
        'TipoUsuario',
    ];
    // Relación con Encargado
    public function encargado()
    {
        return $this->hasOne(Encargado::class);
    }
    // Relación con Usuario
    public function estudiante()
    {
        return $this->hasOne(Estudiante::class);
    }
}


