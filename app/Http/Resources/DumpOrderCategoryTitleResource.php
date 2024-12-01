<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DumpOrderCategoryTitleResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'dump_order_category_id' => $this->dump_order_category_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
