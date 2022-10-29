<?php

namespace App\Imports;

use App\Models\ListPoint;
use Maatwebsite\Excel\Concerns\ToModel;

class ListPointImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new ListPoint([
            'stt' => $row[0],
            'name' => $row[1],
            'date' => $row[2],
            'gmail' => $row[3],
            'di' => $row[4],
            'muon' => $row[5],
            'nghi' => $row[6],
        ]);
    }
}
