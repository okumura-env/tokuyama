<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class McmCoalUsageScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date_id' => ['required', 'integer', 'exists:dates,id'],
            'planned_amount' => ['required','integer'],
            'usage_amount' => ['required','integer'],
            'temporary_amount' => ['required','integer'],
            'note' => ['nullable', 'string'],
        ];
    }
}
