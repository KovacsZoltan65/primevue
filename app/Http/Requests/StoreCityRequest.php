<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCityRequest extends FormRequest
{
    /**
     * Határozza meg, hogy a felhasználó jogosult-e erre a kérésre.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Szerezze meg a kérelemre vonatkozó érvényesítési szabályokat.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required','string',
                "min:{$this->validationRules['minStringLength']}",
                "max:{$this->validationRules['maxStringLength']}",
                Rule::unique('cities', 'name')
            ],
            'country_id' => ['required', 'integer'],
            'region_id' => ['required', 'integer'],
            'latitude' => ["decimal: {$this->validationRules['DecLength']},{$this->validationRules['DecDigits']}"],
            'longitute' => ["decimal: {$this->validationRules['DecLength']},{$this->validationRules['DecDigits']}"],
            'active' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name' => [
                'required' => __('validate_required'),
                'string' => __('validate_string'),
                'min' => __('validate_min.numeric', ['min' => $this->validationRules['minStringLength']]),
                'max' => __('validate_max.numeric', ['max' => $this->validationRules['maxStringLength']]),
                'unique' => __('validate_unique'),
            ],
            'country_id' => [
                'required' => __('validate_required'),
                'integer' => __('validate_integer'),
            ],
            'region_id' => [
                'required' => __('validate_required'),
                'integer' => __('validate_integer'),
            ],
            'latitude' => [
                'decimal' => __('validate_decimal', ['decimal' => ($this->DecLength - $this->validationRules['DecDigits'])]),
            ],
            'longitude' => [
                'decimal' => __('validate_decimal', ['decimal' => ($this->DecLength - $this->validationRules['DecDigits'])]),
            ],
            'active' => [
                'required' => __('validate_required'),
                'boolean' => __('validate_boolean'),
            ],
        ];
    }

}
