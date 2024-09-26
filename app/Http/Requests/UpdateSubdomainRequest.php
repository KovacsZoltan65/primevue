<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubdomainRequest extends FormRequest
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
            'subdomain' => ['required', 'string', 'min:2', 'max:255'],
            'url' => ['required', 'string', 'min:2', 'max:255'],
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'db_host' => ['required', 'string', 'min:2', 'max:255'],
            'db_port' => ['integer'],
            'db_name' => ['required', 'string', 'min:2', 'max:255'],
            'db_user' => ['required', 'string', 'min:2', 'max:255'],
            'db_password' => ['required', 'string', 'min:2', 'max:255'],
            'notification' => ['required', 'in:0,1'],
            'state_id' => ['required', 'in:0,1'],
            'is_mirror' => ['required', 'in:0,1'],
            'sso' => ['required', 'in:0,1'],
            'access_control_system' => ['required', 'in:0,1'],
            //'last_export' => [''] TIMESTAMP NULL DEFAULT NULL COMMENT 'Utoldó export',
        ];
    }
    
    public function messages(): array
    {
        return [
            //
        ];
    }
}
