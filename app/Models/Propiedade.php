<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Especialidade;
use App\Models\TipoBeca;
use App\Models\Seccione;

class Propiedade extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];

    public function especialidade(){
        return $this->hasOne(Especialidade::class);
    }


    public function tipoBeca(){
        return $this->hasOne(TipoBeca::class);
    }


    public function seccione(){
        return $this->hasOne(Seccione::class);
    }



}
