<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompSettingRequest extends FormRequest
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
        // Lekérdezzük a beállítás metaadatát
        $key = $this->route('key'); // Feltételezzük, hogy az útvonal tartalmazza a kulcsot
        $metadata = SettingMetadata::where('key', $key)->first();
        
        $rules = [
            'key' => 'required',
            'value' => 'required',
        ];
        
        if ($metadata) {
            $rules['value'] = json_decode($metadata->validation_rules, true) ?? [];
        }
        
        return $rules;
    }
}
