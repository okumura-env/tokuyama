<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RuleResource extends JsonResource
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
            'name' => $this->name,
            'day_of_week' => $this->day_of_week,
            'vehicle_id' => $this->vehicle_id,
            'priority' => $this->priority,
            'mcm_task_type' => $this->mcmTaskType,
        ];
    }
}
