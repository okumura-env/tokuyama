<?php

namespace App\Imports;

use App\Models\DumpOrder;
use Maatwebsite\Excel\Concerns\ToModel;

class DumpOrderImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new DumpOrder([
            //
        ]);
    }
}
