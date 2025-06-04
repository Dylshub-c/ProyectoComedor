<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Encargado;
use App\Models\Persona;
use Illuminate\Support\Facades\DB;
use Exception;

class EncargadoController extends Controller
{
    public function index()
    {
        $encargados = Encargado::with('persona')->get();
        return view('encargados.index', compact('encargados'));
    }

    public function create()
    {
        return view('encargados.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => 'required|string|max:100',
            'PrimerApellido' => 'required|string|max:100',
            'SegundoApellido' => 'nullable|string|max:100',
            'Cedula' => 'required|string|max:20|unique:personas,Cedula',
            'TipoUsuario' => 'required|string|max:50',
            'correo' => 'required|email|unique:encargados,correo',
        ]);

        try {
            DB::beginTransaction();

            $persona = Persona::create($request->only([
                'Nombre', 'PrimerApellido', 'SegundoApellido', 'Cedula', 'TipoUsuario'
            ]));

            $persona->encargado()->create([
                'correo' => $request->correo
            ]);

            DB::commit();

            return redirect()->route('encargados.index')->with('success', 'Encargado registrado correctamente');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Ocurrió un error al registrar el encargado.']);
        }
    }

    public function destroy(string $id)
    {
        $encargado = Encargado::findOrFail($id);
        $persona = $encargado->persona;

        $persona->estado = !$persona->estado;
        $persona->save();

        $msg = $persona->estado ? 'Encargado restaurado' : 'Encargado desactivado';
        return redirect()->route('encargados.index')->with('success', $msg);
    }
}
