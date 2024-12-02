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
            'vehicle_id' => ['required', 'integer'],
            'daily_vehicle_assignment_id' => ['required', 'integer'],
            'dump_order_category_id' => ['required', 'integer'],
            'dump_order_category_title_id' => ['required', 'integer'],
            'boiler_number' => ['nullable', 'string'],
            'status' => ['required', 'string'],
            'is_preloaded' => ['required', 'boolean'],
            'vehicle_number' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
