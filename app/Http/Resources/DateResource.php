<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DateResource extends JsonResource
{
    public function toArray($request):array
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'jetpack_note' => $this->jetpack_note,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
