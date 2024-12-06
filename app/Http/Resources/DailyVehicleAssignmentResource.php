<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DailyVehicleAssignmentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'date_id' => $this->date_id,
            'vehicle_id' => $this->vehicle_id,
            'work_type_id' => $this->work_type_id,
            'worker_id' => $this->worker_id,
            'sub_worker' => $this->sub_worker,
            'start_time' => $this->start_time,
            'task_priority' => $this->task_priority,
            'driver_task_order' => $this->driver_task_order,
            'note' => $this->note,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
