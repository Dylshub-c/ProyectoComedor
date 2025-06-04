<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ZipArchive;
use Illuminate\Support\Facades\File;

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

        $zip = new ZipArchive;
        $file = $request->file('zip');
        $destinationPath = public_path('fotos');

        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        if ($zip->open($file) === true) {
            // Extraemos solo los archivos dentro del ZIP sin carpetas
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);

                // Ignorar directorios, solo archivos
                if (substr($filename, -1) != '/') {
                    // Extraemos el contenido del archivo
                    $content = $zip->getFromIndex($i);

                    // Extraemos el nombre del archivo sin ruta (basename)
                    $baseName = basename($filename);

                    // Guardamos el archivo directamente en la carpeta destino
                    file_put_contents($destinationPath . DIRECTORY_SEPARATOR . $baseName, $content);
                }
            }
            $zip->close();

            return back()->with('success', 'Fotos descomprimidas correctamente.');
        }

        return back()->withErrors(['error' => 'No se pudo descomprimir el archivo.']);
        }
}
