<?php

namespace App\Imports;

use App\Models\Propiedade;
use App\Models\Especialidade;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class EspecialidadesImport implements ToCollection
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $nombre = trim($row[0]);

            if ($nombre && !Propiedade::where('nombre', $nombre)->exists()) {
                $propiedade = Propiedade::create(['nombre' => $nombre]);
                $propiedade->especialidade()->create();
            }
        }
    }
}
