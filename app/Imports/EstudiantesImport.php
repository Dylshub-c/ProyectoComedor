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
use Maatwebsite\Excel\Concerns\ToCollection;
use Exception;

class EstudiantesImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        DB::beginTransaction();

        try {
         foreach ($rows->skip(1) as $row) {
            // Persona
            $persona = Persona::firstOrCreate(
                ['Cedula' => $row[3]],
                [
                    'Nombre' => $row[0],
                    'PrimerApellido' => $row[1],
                    'SegundoApellido' => $row[2],
                    'TipoUsuario' => $row[4],
                ]
            );

            if (!$persona->id) {
                throw new Exception("No se pudo crear o encontrar persona con cédula: " . $row[3]);
            }

            // Especialidad
            $especialidad = Especialidade::firstOrCreate([
                'propiedade_id' => Propiedade::firstOrCreate(['nombre' => $row[5]])->id
            ]);

            // Sección
            $seccion = Seccione::firstOrCreate([
                'propiedade_id' => Propiedade::firstOrCreate(['nombre' => $row[6]])->id
            ]);

            // Tipo de beca
            $tipoBeca = TipoBeca::firstOrCreate([
                'propiedade_id' => Propiedade::firstOrCreate(['nombre' => $row[7]])->id
            ]);

            // ** Validar que el estudiante con esa persona no exista **
            $estudianteExistente = Estudiante::where('persona_id', $persona->id)->first();

            if (!$estudianteExistente) {
                Estudiante::create([
                    'persona_id' => $persona->id,
                    'especialidade_id' => $especialidad->id,
                    'seccione_id' => $seccion->id,
                    'tipo_beca_id' => $tipoBeca->id,
                    'foto' => 'fotos/' . $row[8],
                ]);
            }
            // Si ya existe el estudiante, no hagas nada (o actualizar si quieres)
        }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

