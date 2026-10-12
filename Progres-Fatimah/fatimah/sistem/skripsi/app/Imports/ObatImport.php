<?php

namespace App\Imports;

use App\Models\Obat;
use Maatwebsite\Excel\Concerns\ToModel;

class ObatImport implements ToModel
{
    public function model(array $row)
    {
        $exists = Obat::where('kode_obat', $row[0])->exists();
        if (!$exists) {
            return new Obat([
                'kode_obat' => $row[0],
                'nama_obat' => $row[1],
                'stok'      => $row[2] ?? 0,
            ]);
        }
    }
    

    
}
