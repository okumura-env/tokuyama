<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JetpackScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'date_id' => $this->date_id,
            'vehicle_id' => $this->vehicle_id,
            'date_vehicle_id' => $this->date_vehicle_id,
            'cell_number' => $this->cell_number,
            'order_sequence' => $this->order_sequence,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
