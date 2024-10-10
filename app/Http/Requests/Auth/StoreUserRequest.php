<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
                'min:' . APP_MIN_STRING_LENGTH,
                'max:' . APP_MAX_STRING_LENGTH,
            ],
            'password' => Rule::password()->min(8)->mixedCase()->symbols()
                ->uncompromised(), 
                    function($attribute, $value, $fail): void
                    {
                        if( preg_match_all('/[0-9]/', $value) < 2 )
                        {
                            $fail('The :attribute must contain at least 2 numbers.');
                        }

                        if( preg_match_all('/[a-zA-Z]/', $value) < 3 )
                        {
                            $fail('The :attribute must contain at least 3 letters.');
                        }
                    }
        ];
    }
}
