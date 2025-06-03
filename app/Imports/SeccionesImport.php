<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Seccione;
use App\Models\Propiedade;


class SeccionesImport implements ToCollection
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $nombre = trim($row[1]);

            if ($nombre && !Propiedade::where('nombre', $nombre)->exists()) {
                $propiedade = Propiedade::create(['nombre' => $nombre]);
                $propiedade->seccione()->create();
            }
        }
    }
}
