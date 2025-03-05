<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class StoreWorkplanRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
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
                'required', 'string', 
                "min:{$this->validationRules['minStringLength']}", 
                "max:{$this->validationRules['maxStringLength']}",
                Rule::unique('workplans', 'name'),
            ],
            'company_id' => ['required', 'exists:companies,id'],
            'acs_id' => ['integer', 'exists:acs,id'],
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
            ],
            'company_id' => [
                'required' => __('validate_required'),
                'exists' => __('validate_exists'),
            ],
            'acs_id' => [
                'integer' => __('validate_integer'),
                'exists' => __('validate_exists'),
            ],
        ];
    }
}
