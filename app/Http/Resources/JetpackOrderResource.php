<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\JetpackDestination;

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
            'jetpack_destination_id' => $this->jetpack_destination_id,
            'jetpack_destination_name' => JetpackDestination::find($this->jetpack_destination_id)->name,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'note' => $this->note,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
