<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DumpOrderCategoryTitleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // 必要に応じて認可ロジックを記述
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'dump_order_category_id' => ['required', 'integer'],
        ];
    }
}
