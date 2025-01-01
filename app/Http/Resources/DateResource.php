<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class DateResource extends JsonResource
{
    public function toArray($request):array
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'day_of_week' => Carbon::parse($this->date)->locale('ja')->dayName, // 曜日
            'jetpack_note' => $this->jetpack_note,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
