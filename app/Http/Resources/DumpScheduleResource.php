<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DumpScheduleResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'date_id' => $this->date_id,
            'vehicle_id' => $this->vehicle_id,
            'dump_order_category_id' => $this->dump_order_category_id,
            'dump_order_category_title_id' => $this->dump_order_category_title_id,
            'dump_order_category_title' => $this->dump_order_category_title,
            'schedule_type' => $this->schedule_type,
            'sort_order' => $this->sort_order,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
