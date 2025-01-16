<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubdomainRequest extends FormRequest
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
            'subdomain' => ['required'],
            'url' => ['required'],
            'name' => ['required'],
            'db_host' => ['required'],
            'db_port' => ['required'],
            'db_name' => ['required'],
            'db_user' => ['required'],
            'db_password' => ['required'],
        ];
    }
}
