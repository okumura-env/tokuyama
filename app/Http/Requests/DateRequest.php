<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // 認可を常に許可
    }

    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'jetpack_note' => ['string'],
        ];
    }
}
