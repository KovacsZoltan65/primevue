<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CentralRequest extends FormRequest
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
        $validationRules = json_decode(file_get_contents(resource_path('js/validationRules.js')), true);
        
        return [
            'field_name' => 'required|string|max:' . $validationRules['maxStringLength'],
        ];
    }
}
