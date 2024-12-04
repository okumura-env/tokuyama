<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DumpOrderResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'date_id' => $this->date_id,
            'vehicle_id' => $this->vehicle_id,
            'dump_schedule_id' => $this->dump_schedule_id,
            'daily_vehicle_assignment_id' => $this->daily_vehicle_assignment_id,
            'boiler_number' => $this->boiler_number,
            'status' => $this->status,
            'is_preloaded' => $this->is_preloaded,
            'vehicle_number' => $this->vehicle_number,
            'notes' => $this->notes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
