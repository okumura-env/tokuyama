<?php

namespace App\Imports;

use App\Models\DumpOrder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class DumpOrderImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            DumpOrder::create([
                'date_id' => $row[0],
                'vehicle_id' => $row[1],
                'dump_schedule_id' => $row[2],
                'daily_vehicle_assignment_id' => $row[3],
                'boiler_number' => $row[4],
                'status' => $row[5],
                'is_preloaded' => $row[6],
                'vehicle_number' => $row[7],
                'notes' => $row[8],
            ]);
        }
    }
}

