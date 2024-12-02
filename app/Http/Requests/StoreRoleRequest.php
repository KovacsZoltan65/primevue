<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class StoreRoleRequest extends BaseRequest
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
                "mix:{$this->validationRules['minStringLength']}",
                "max:{$this->validationRules['maxStringLength']}",
                Rule::unique('rules', 'name'),
            ],
            'guard_name' => [
                'required','string',
                "mix:{$this->validationRules['minStringLength']}",
                "max:{$this->validationRules['maxStringLength']}",
                Rule::unique('rules', 'guard_name'),
            ],
        ];
    }
}
