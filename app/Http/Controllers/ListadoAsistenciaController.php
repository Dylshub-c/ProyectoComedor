<?php
namespace App\Http\Controllers;

use App\Models\ListadoAsistencia;
use App\Models\Asistencia;
use App\Models\Estudiante;
use Illuminate\Http\Request;

class ListadoAsistenciaController extends Controller
{
    public function index()
    {
        $listados = ListadoAsistencia::with(['asistencia', 'estudiante'])->latest()->get();
        return view('listado_asistencias.index', compact('listados'));
    }

    public function create()
    {
        $asistencias = Asistencia::all();
        $estudiantes = Estudiante::all();
        return view('listado_asistencias.create', compact('asistencias', 'estudiantes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'observaciones' => 'nullable|string',
            'estudiante_id' => 'required|exists:estudiantes,id',
            'asistencia_id' => 'required|exists:asistencias,id',
        ]);

        ListadoAsistencia::create($request->all());
        return redirect()->route('listado_asistencias.index')->with('success', 'Listado registrado');
    }

    public function destroy($id)
    {
        $listado = ListadoAsistencia::findOrFail($id);
        $listado->delete();
        return redirect()->route('listado_asistencias.index')->with('success', 'Registro eliminado');
    }
}
