<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RuleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'day_of_week' => ['required', 'string'],
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'priority' => ['required', 'integer'],
            'mcm_task_type_id' => ['required', 'exists:mcm_task_types,id'],
        ];
    }
}
