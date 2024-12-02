<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // 必要に応じて認可ロジックを記述
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'], // nameフィールドのバリデーション
        ];
    }
}
