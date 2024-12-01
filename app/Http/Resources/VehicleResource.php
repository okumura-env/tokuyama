<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'number' => $this->number,
            'capacity' => $this->capacity,
            'vehicle_type_id' => $this->vehicle_type_id,
            'partner_id' => $this->partner_id,
            'worker_id' => $this->worker_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
