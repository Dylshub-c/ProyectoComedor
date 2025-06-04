<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encargado extends Model
{
    use HasFactory;

    protected $primaryKey = 'idEncargado';
    protected $fillable = [
        'persona_id',
        'correo',
    ];

    // Relación: un encargado pertenece a persona
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id', 'idPersona');
    }
}
