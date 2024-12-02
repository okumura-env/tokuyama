<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DailyVehicleAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date_id' => ['required', 'integer'],
            'work_type_id' => ['required', 'integer'],
            'vehicle_id' => ['required', 'integer'],
            'worker_id' => ['required', 'integer'],
            'sub_worker' => ['nullable', 'string'],
            'start_time' => ['required', 'date_format:H:i'],
            'task_order' => ['required', 'integer'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
