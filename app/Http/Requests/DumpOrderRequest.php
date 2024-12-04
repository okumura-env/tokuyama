<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DumpOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date_id' => ['required', 'integer'],
            'vehicle_id' => ['nullable', 'integer'],
            'schedule_id' => ['required', 'integer'],
            'daily_vehicle_assignment_id' => ['nullable', 'integer'],
            'boiler_number' => ['nullable', 'string'],
            'status' => ['required', 'boolean'],
            'is_preloaded' => ['required', 'boolean'],
            'vehicle_number' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
