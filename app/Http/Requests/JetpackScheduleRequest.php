<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JetpackScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'date_id' => ['required', 'integer'],
            'vehicle_id' => ['nullable', 'integer'],
            'date_vehicle_id' => ['nullable', 'integer'],
            'cell_number' => ['nullable', 'integer'],
            'order_sequence' => ['nullable', 'integer'],
        ];
    }
}
