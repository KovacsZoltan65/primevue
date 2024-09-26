<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCityRequest extends FormRequest
{
    /**
     * Határozza meg, hogy a felhasználó jogosult-e erre a kérésre.
     */
    public function authorize(): bool
    {
        return true;
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
            // Egyedinek kell lennie a városok nevei között a jelenlegi város kivételével.
            'name' => [
                'request', 'string', 'min:2', 'max:255', 
                Rule::unique('cities', 'name')->ignore($this->id),
            ],
            // Az ország azonosítójának jelen kell lennie, és egész számnak kell lennie.
            'country_id' => ['required', 'integer'],
            // A régió azonosítójának jelen kell lennie, és egész számnak kell lennie.
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
            'name' => '',
            'country_id' => '',
            'region_id' => '',
            'latitude' => '',
            'longitute' => '',
            'active' => '',
        ];
    }
    
}
