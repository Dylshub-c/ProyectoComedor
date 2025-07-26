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
            DB::beginTransaction();

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

                // Normaliza nombres
                $nombreEspecialidad = trim(ucwords(strtolower($row[5])));
                $nombreSeccion      = trim(ucwords(strtolower($row[6])));
                $nombreBeca         = trim(ucwords(strtolower($row[7])));

                // Propiedades
                $propEspecialidad = Propiedade::firstOrCreate(['nombre' => $nombreEspecialidad]);
                $propSeccion      = Propiedade::firstOrCreate(['nombre' => $nombreSeccion]);
                $propBeca         = Propiedade::firstOrCreate(['nombre' => $nombreBeca]);

                // Relaciones
                $especialidad = Especialidade::firstOrCreate(['propiedade_id' => $propEspecialidad->id]);
                $seccion      = Seccione::firstOrCreate(['propiedade_id' => $propSeccion->id]);
                $tipoBeca     = TipoBeca::firstOrCreate(['propiedade_id' => $propBeca->id]);

                // Verificar si ya existe el estudiante
                $estudianteExistente = Estudiante::where('persona_id', $persona->id)->first();

                if (!$estudianteExistente) {
                    Estudiante::create([
                        'persona_id'       => $persona->id,
                        'especialidade_id' => $especialidad->id,
                        'seccione_id'      => $seccion->id,
                        'tipo_beca_id'     => $tipoBeca->id,
                        'foto'             => 'fotos/' . $row[8], // Asegúrate de que el archivo exista si esto es real
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }
}

