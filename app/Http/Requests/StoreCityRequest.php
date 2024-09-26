<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCityRequest extends FormRequest
{
    /**
     * Határozza meg, hogy a felhasználó jogosult-e erre a kérésre.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Szerezze meg a kérelemre vonatkozó érvényesítési szabályokat.
     * 
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // A város neve karakterlánc, minimum 2, maximum 255 karakter hosszú.
            // Egyedinek kell lennie a városok nevei között.
            'name' => ['required', 'string', 'min:2', 'max:255', 'unique:cities,name'],
            // A város országának azonosítója jelen kell lennie, 
            // és egész számnak kell lennie.
            'country_id' => ['required', 'integer'],
            // A város régiójának azonosítója jelen kell lennie, 
            // és egész számnak kell lennie.
            'region_id' => ['required', 'integer'],
            // A város szélessége tizedesjegy, legfeljebb 10 digit hosszú, 
            // amiből kettő egész szám.
            'latitude' => ['decimal:10,2'],
            // A város hosszúsága tizedesjegy, legfeljebb 10 digit hosszú, 
            // amiből kettő egész szám.
            'longitute' => ['decimal:10,2'],
            // A várost aktívként vagy inaktívként kell megjelölni.
            'active' => ['required', 'boolean'],
        ];
    }
    public function messages(): array
    {
        return [
            'name' => [
                'required' => '',
                'string' => '',
                'min' => '',
                'max' => '',
                'unique' => '',
            ],
            'country_id' => [
                'required' => '',
                'integer' => '',
            ],
            'region_id' => [
                'required' => '',
                'integer' => '',
            ],
            'latitude' => [
                'decimal' => '',
            ],
            'longitute' => [
                'decimal' => '',
            ],
            'active' => [
                'required' => '',
                'boolean' => '',
            ],
        ];
    }
    
}
