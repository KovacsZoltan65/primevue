<?php

namespace App\Http\Requests;

//use Illuminate\Foundation\Http\FormRequest;

class StoreSubdomainRequest extends BaseRequest
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
            'subdomain' => [
                'required', 'string', 
                "min:{$this->validationRules['minStringLength']}",
                "max:{$this->validationRules['maxStringLength']}"
            ],
            'url' => ['required', 'string', 
                "min:2", "max:255"
            ],
            'name' => ['required', 'string', 
                "min:{$this->validationRules['minStringLength']}",
                "max:{$this->validationRules['maxStringLength']}"
            ],
            'db_host' => [
                'required', 'string', 
                "min:{$this->validationRules['minStringLength']}",
                "max:{$this->validationRules['maxStringLength']}"
            ],
            'db_port' => ['integer'],
            'db_name' => [
                'required', 'string', 
                "min:{$this->validationRules['minStringLength']}",
                "max:{$this->validationRules['maxStringLength']}"
            ],
            'db_user' => [
                'required', 'string', 
                "min:{$this->validationRules['minStringLength']}",
                "max:{$this->validationRules['maxStringLength']}"
            ],
            'db_password' => [
                'required', 'string', 
                "min:{$this->validationRules['minStringLength']}",
                "max:{$this->validationRules['maxStringLength']}"
            ],
            'notification' => [
                'required', 
                "in:{$this->validationRules['bool_false']},{$this->validationRules['bool_true']}"
            ],
            'state_id' => [
                'required', 
                "in:{$this->validationRules['bool_false']},{$this->validationRules['bool_true']}"
            ],
            'is_mirror' => [
                'required', 
                "in:{$this->validationRules['bool_false']},{$this->validationRules['bool_true']}"
            ],
            'sso' => [
                'required', 
                "in:{$this->validationRules['bool_false']},{$this->validationRules['bool_true']}"
            ],
            'access_control_system' => [
                'required', 
                "in:{$this->validationRules['bool_false']},{$this->validationRules['bool_true']}"
            ],
        ];
    }
}
