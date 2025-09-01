<?php

namespace App\Imports;

use App\Models\Persona;
use App\Models\Especialidade;
use App\Models\Propiedade;
use App\Models\Seccione;
use App\Models\TipoBeca;
use App\Models\Estudiante;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToCollection;
use Exception;

class EstudiantesImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        DB::beginTransaction();

        try {
            foreach ($rows->skip(1) as $index => $row) {
                // Validar columnas mínimas
                if (count($row) < 9) {
                    throw new Exception("Fila " . ($index + 2) . " incompleta. Se requieren al menos 9 columnas.");
                }

                // Crear o encontrar persona
                $persona = Persona::firstOrCreate(
                    ['Cedula' => $row[3]],
                    [
                        'Nombre'         => $row[0],
                        'PrimerApellido' => $row[1],
                        'SegundoApellido'=> $row[2],
                        'TipoUsuario'    => $row[4],
                    ]
                );

                // Normalizar nombres
                $nombreEspecialidad = ucfirst(strtolower(trim($row[5]))); // Contabilidad
                $nombreSeccion      = strtoupper(trim($row[6]));          // 12-D
                $becasTexto         = trim($row[7]);
                $nombreFoto         = trim($row[8]); // solo nombre del archivo, ej: juan.jpg

                // Crear propiedades y relaciones
                $propEspecialidad = Propiedade::firstOrCreate(['nombre' => $nombreEspecialidad]);
                $propSeccion      = Propiedade::firstOrCreate(['nombre' => $nombreSeccion]);

                $especialidad = Especialidade::firstOrCreate(['propiedade_id' => $propEspecialidad->id]);
                $seccion      = Seccione::firstOrCreate(['propiedade_id' => $propSeccion->id]);

                // ----------------------
                // Procesar becas (insensible a mayúsculas/minúsculas)
                // ----------------------
                $tipoBecasIds = [];
                if (!empty($becasTexto)) {
                    $becas = preg_split('/\s*,\s*/', $becasTexto, -1, PREG_SPLIT_NO_EMPTY);

                    foreach ($becas as $nombreBeca) {
                        $nombreBeca = trim($nombreBeca);
                        if (empty($nombreBeca)) continue;

                        $tipoBeca = TipoBeca::whereHas('propiedade', function ($q) use ($nombreBeca) {
                            $q->whereRaw('LOWER(nombre) = ?', [strtolower($nombreBeca)]);
                        })->first();

                        if (!$tipoBeca) {
                            $propiedadBeca = Propiedade::firstOrCreate(['nombre' => ucwords(strtolower($nombreBeca))]);
                            $tipoBeca = TipoBeca::create(['propiedade_id' => $propiedadBeca->id]);
                        }

                        $tipoBecasIds[] = $tipoBeca->id;
                    }
                }

                // ----------------------
                // Guardar estudiante
                // ----------------------
                $estudiante = Estudiante::updateOrCreate(
                    ['persona_id' => $persona->id],
                    [
                        'especialidade_id' => $especialidad->id,
                        'seccione_id'      => $seccion->id,
                        'foto'             => $nombreFoto ?: 'default.jpg', // solo nombre por ahora
                    ]
                );

                // Sincronizar becas
                if (!empty($tipoBecasIds)) {
                    $estudiante->tipoBecas()->syncWithoutDetaching($tipoBecasIds);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
