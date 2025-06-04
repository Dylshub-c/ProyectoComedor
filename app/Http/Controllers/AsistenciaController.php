<?php
namespace App\Http\Controllers;

use App\Models\Asistencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsistenciaController extends Controller
{
    public function index()
    {
        $asistencias = Asistencia::latest()->get();
        return view('asistencias.index', compact('asistencias'));
    }

    public function create()
    {
        return view('asistencias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha_hora' => 'required|date'
        ]);

        DB::beginTransaction();
        try {
            $tipos = ['desayuno', 'almuerzo'];
            $estados = ['presente', 'ausente'];

            foreach ($tipos as $tipo) {
                foreach ($estados as $estado) {
                    Asistencia::create([
                        'fecha_hora' => $request->fecha_hora,
                        'tipo_asistencia' => $tipo,
                        'estado' => $estado
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('asistencias.index')->with('success', 'Asistencias creadas.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $asistencia = Asistencia::findOrFail($id);
        $asistencia->delete();
        return redirect()->route('asistencias.index')->with('success', 'Asistencia eliminada');
    }
}