<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class StoreCountryRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
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
                Rule::unique('countries', 'name'),
            ],
            'code' => [
                'required','string',
                "min:{$this->validationRules['maxCodeLength']}",
                "max:{$this->validationRules['maxCodeLength']}",
                Rule::unique('countries', 'code'),
            ],
        ];
    }
    
    public function messages()
    {
        return [
            'name' => [
                'required' => __('validate_required'),
                'string' => __('validate_string'),
                'min' => __('validate_min.numeric'),
                'max' => __('validate_max.numeric'),
                'unique' => __('validate_unique'),
            ],
            'email' => [
                'required' => __('validate_required'),
                'min' => __('validate_min.numeric'),
                'max' => __('validate_max.numeric'),
                'unique' => __('validate_unique'),
            ]
        ];
    }
}
