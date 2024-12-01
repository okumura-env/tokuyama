<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VehicleRequest extends FormRequest
{
    public function authorize()
    {
        return true; // 必要に応じて権限チェックを追加
    }

    public function rules()
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'unique:vehicles,code'],
            'number' => ['required', 'string', 'max:20'],
            'capacity' => ['nullable', 'integer'],
            'vehicle_type_id' => ['required', 'integer', 'exists:vehicle_types,id'],
            'partner_id' => ['nullable', 'integer', 'exists:partners,id'],
            'worker_id' => ['nullable', 'integer', 'exists:workers,id'],
        ];
    }
}
