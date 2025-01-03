<?php

namespace App\Http\Requests;

class DeleteComaniesRequest extends BaseRequest
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
            'ids' => [
                'required',
                'array',
                "min:{$this->validationRules['array_min_length']}"
            ],
            'ids.*' => [
                'integer', 
                'exists:cities,id'
            ],
        ];
    }
}
