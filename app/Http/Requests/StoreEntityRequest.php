<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEntityRequest extends BaseRequest
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
            ],
            'email' => ['required','email'],
        ];
    }

    public function messages()
    {
        return [
            'name' => [
                'required' => __('validate_required'),
                'string' => __('validate_string'),
                'min' => __('validate_min.numeric', ['min' => $this->validationRules['minStringLength']]),
                'max' => __('validate_max.numeric', ['max' => $this->validationRules['maxStringLength']]),
            ],
            'email' => [
                'required' => __('validate_required'),
                'email' => __('validate_email'),
            ]
        ];
    }
}
