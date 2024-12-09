<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DumpScheduleRequest extends FormRequest
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
            'dump_order_category_id' => ['required', 'integer'],
            'dump_order_category_title_id' => ['nullable', 'integer'],
            'dump_order_category_title' => ['nullable', 'string', 'max:255'],
            'schedule_type' => ['required', 'string', 'max:255'],
            'sort' => ['required', 'integer'],
        ];
    }
}
