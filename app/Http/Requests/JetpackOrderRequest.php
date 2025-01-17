<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JetpackOrderRequest extends FormRequest
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
            'jetpack_schedule_id' => ['nullable', 'integer'],
            'jetpack_destination_route_id' => ['required', 'integer'],
            'quantity' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'string', 'max:255'],
            'note' => ['nullable', 'string'],
        ];
    }
}
