<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Persona extends Model
{

    use HasFactory;
    protected $primaryKey = 'idPersona';


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
    public function usuario()
    {
        return $this->hasOne(User::class);
    }
}


