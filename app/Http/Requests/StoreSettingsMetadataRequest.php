<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSettingsMetadataRequest extends FormRequest
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
            'key' => ['required','string','unique:settings_metadata,key'],
            'label' => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'type' => ['required','in:string,integer,boolean,json'],
            'level' => ['required','in:application,company'],
            'is_required' => ['boolean'],
            'default_value' => ['nullable'],
            'validation_rules' => ['nullable','json'],
        ];
    }
}
