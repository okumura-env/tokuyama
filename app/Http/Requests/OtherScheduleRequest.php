<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OtherScheduleRequest extends FormRequest
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
            'schedule_id' => ['required', 'integer'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
