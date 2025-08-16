<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReporteController extends Controller
{
     public function descargar()
    {
        
        return view('Reportes.DescargarReporte');

    }
}
