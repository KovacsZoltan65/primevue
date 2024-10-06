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
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        //$validationRules = json_decode(file_get_contents(resource_path('js/validationRules.json')), true);
        
//\Log::info('$validationRules: ' . print_r($validationRules['minStringLength'], true));
//\Log::info('$validationRules: ' . print_r($validationRules['maxStringLength'], true));
        /*
        $aa = [
            'fieldName' => [
                'required', 'string', 
                "min:{$validationRules['minStringLength']}", 
                "max:{$validationRules['maxStringLength']}"
            ]
        ];
        */

        $aa = ['fieldName' => 'required|string|min:5|max:6'];

\Log::info('$this->all(): ' . print_r($this->all(), true));
        
        return $aa;
    }
}
