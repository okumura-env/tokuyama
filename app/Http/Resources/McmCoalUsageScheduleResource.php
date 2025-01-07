<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class McmCoalUsageScheduleResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'date_id' => $this->date_id,
            'planned_amount' => $this->planned_amount,
            'usage_amount' => $this->usage_amount,
            'temporary_amount' => $this->temporary_amount,
            'note' => $this->note,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
