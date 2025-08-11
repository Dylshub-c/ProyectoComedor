<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Encargado;
use App\Models\Persona;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Mail\AdminRegisteredMail;

class EncargadoController extends Controller
{
    public function index()
    {
        $encargados = Encargado::with('persona')->get();
        return view('admin.info', compact('encargados'));
    }

    public function create()
    {
        return view('Admin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'PrimerApellido' => 'required|string|max:100',
            'SegundoApellido' => 'nullable|string|max:100',
            'cedula' => 'required|string|unique:personas,Cedula',
            'email' => 'required|email|unique:encargados,correo',
        ], [
            'cedula.unique' => 'El encargado ya está registrado en el sistema.',
            'email.unique' => 'El correo ya está registrado.',
        ]);


        DB::beginTransaction();

        try {
            // Crear persona
            $persona = Persona::create([
                'Nombre' => $request->nombre,
                'PrimerApellido' => $request->PrimerApellido,
                'SegundoApellido' => $request->SegundoApellido,
                'Cedula' => $request->cedula,
                'TipoUsuario' => 'admin',
            ]);

            // Crear encargado asociado
            $encargado = $persona->encargado()->create([
                'correo' => $request->email,
            ]);

            // Crear usuario para login (si aplicas)
            $password = Str::random(10);
            $user = User::create([
                'persona_id' => $persona->id,
                'email' => $request->email,
                'password' => Hash::make($password),
            ]);

            // Asignar rol "Encargado" (asegúrate de que exista)
            Role::firstOrCreate(['name' => 'Administrador']);
            $user->assignRole('Administrador');
            $nombre = $request->nombre;
            Mail::to($user->email)->send(new AdminRegisteredMail($request->email, $password, $nombre));

            DB::commit();

            return redirect()->route('encargados.informacion')
                            ->with('success', 'Encargado creado correctamente y usuario generado.');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al guardar: ' . $e->getMessage()])->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Nombre' => 'required|string|max:100',
            'PrimerApellido' => 'required|string|max:100',
            'SegundoApellido' => 'nullable|string|max:100',
            'Cedula' => 'required|string|max:20|unique:personas,Cedula,' . $id,
            'correo' => 'required|email|unique:encargados,correo,' . $id . ',persona_id',
        ]);

        try {
            DB::beginTransaction();

            // Buscar la persona
            $persona = Persona::findOrFail($id);

            // Actualizar datos de persona
            $persona->update([
                'Nombre' => $request->Nombre,
                'PrimerApellido' => $request->PrimerApellido,
                'SegundoApellido' => $request->SegundoApellido,
                'Cedula' => $request->Cedula,
            ]);

            // Actualizar datos del encargado (relación one to one)
            $persona->encargado()->update([
                'correo' => $request->correo,
            ]);

            DB::commit();

            return redirect()->route('encargados.informacion')->with('success', 'Encargado actualizado correctamente');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al actualizar: ' . $e->getMessage()])->withInput();
        }
    }

    public function informacion(Request $request)
    {
        $editar = $request->input('editar') == 1;

        $encargado = null;

        // Primero revisa si tienes encargado guardado en sesión
        if (session()->has('encargado_id')) {
            $encargado = Encargado::with('persona')->find(session('encargado_id'));
        }

        // Si no hay encargado en sesión, buscar por cédula o nombre (solo en POST)
        if ($request->isMethod('post') && ($request->filled('cedula') || $request->filled('nombre'))) {
            $query = Encargado::with('persona');

            if ($request->filled('cedula')) {
                $query->whereHas('persona', function ($q) use ($request) {
                    $q->where('Cedula', $request->cedula);
                });
            }

            if ($request->filled('nombre')) {
                $nombre = $request->nombre;
                $query->whereHas('persona', function ($q) use ($nombre) {
                    $q->whereRaw("CONCAT(Nombre, ' ', PrimerApellido, ' ', SegundoApellido) LIKE ?", ["%{$nombre}%"]);
                });
            }

            $encargado = $query->first();

            if ($encargado) {
                // Guarda el id en sesión para futuras vistas
                session(['encargado_id' => $encargado->id]);
            }
        }

        // Si no hay encargado, limpia la sesión para evitar mostrar info vieja
        if (!$encargado) {
            session()->forget('encargado_id');
        }

        return view('Admin.info', compact('encargado', 'editar'));
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
