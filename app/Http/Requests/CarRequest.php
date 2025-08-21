<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'car_model_id' => ['sometimes', Rule::requiredIf($this->method() === 'POST'),'exists:car_models,id'],
            'year' => ['nullable','integer','min:1900','max:' . ((int)(date('Y')) + 1)],
            'mileage' => ['nullable','integer','min:0'],
            'color' => ['nullable','string','max:50'],
        ];
    }
}
