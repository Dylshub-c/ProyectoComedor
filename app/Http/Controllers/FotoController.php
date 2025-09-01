<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ZipArchive;
use Illuminate\Support\Facades\File;
use App\Models\Estudiante;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class FotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function showForm() {
        return view('fotos.subir-fotos'); // tu vista con el formulario
    }



public function importarFotos(Request $request)
{
    $request->validate([
        'zip' => 'required|file|mimes:zip',
    ]);

    $zip = new \ZipArchive;
    $file = $request->file('zip');
    $destinationPath = storage_path('app/public/fotos'); // Carpeta pública

    if (!\File::exists($destinationPath)) {
        \File::makeDirectory($destinationPath, 0755, true);
    }

    if ($zip->open($file) === true) {
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);

            // Ignorar directorios
            if (substr($filename, -1) != '/') {
                $content = $zip->getFromIndex($i);
                $baseName = basename($filename);

                // Guardar en storage público
                $nombreUnico = uniqid('foto_', true) . '.' . pathinfo($baseName, PATHINFO_EXTENSION);
                Storage::disk('public')->put('fotos/' . $nombreUnico, $content);

                // Buscar estudiante por nombre de archivo original
                $estudiante = Estudiante::where('foto', $baseName)->first();
                if ($estudiante) {
                    $estudiante->foto = 'fotos/' . $nombreUnico;
                    $estudiante->save();
                }
            }
        }
        $zip->close();

        return back()->with('success', 'Fotos asignadas correctamente.');
    }

    return back()->withErrors(['error' => 'No se pudo descomprimir el archivo.']);
}

}
