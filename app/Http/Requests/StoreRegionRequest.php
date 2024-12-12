<?php

namespace App\Http\Requests;


class StoreRegionRequest extends BaseRequest
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
                'required', 'string', 
                "min:{$this->validationRules['minStringLength']}", 
                "max:{$this->validationRules['maxStringLength']}"
            ],
            'code' => [
                'required','string',
                "min:",
                "max:"
            ],
            'country_id' => [
                'required','integer',"exists:countries,id"
            ],
        ];
    }
}
