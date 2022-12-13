<?php

namespace App\Imports;

use App\Models\PublicCompany;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportBigData implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        dd($row);
        return new PublicCompany([
            "district" => $row['kode_kecamatan'],
            "name" => $row[1],
            "owner_name" => $row[2],
            "type" => $row[3],
            "scale" => $row[4],
            "revenue" => $row[5],
            "data_year" => $row[6],
        ]);
    }
}
