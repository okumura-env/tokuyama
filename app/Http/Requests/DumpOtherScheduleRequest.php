<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DumpOtherScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date_id' => ['required', 'integer'],
            'vehicle_id' => ['required', 'integer'],
            'dump_schedule_id' => ['required', 'integer'],
            'note' => ['nullable', 'string'],
        ];
    }
}
