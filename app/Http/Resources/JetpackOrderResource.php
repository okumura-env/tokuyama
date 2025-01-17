<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JetpackOrderResource extends JsonResource
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
            'jetpsck_schedule_id' => $this->jetpsck_schedule_id,
            'jetpack_destination_route_id' => $this->jetpack_destination_route_id,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'note' => $this->note,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
