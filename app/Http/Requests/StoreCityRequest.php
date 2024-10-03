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
            'name' => [
                'required', 'string', 'unique:cities,name',
                "min:" . APP_MIN_STRING_LENGTH, 
                "max:" . APP_MAX_STRING_LENGTH,
            ],
            // A város országának azonosítója jelen kell lennie, 
            // és egész számnak kell lennie.
            'country_id' => ['required', 'integer'],
            // A város régiójának azonosítója jelen kell lennie, 
            // és egész számnak kell lennie.
            'region_id' => ['required', 'integer'],
            // A város szélessége tizedesjegy, legfeljebb 10 digit hosszú, 
            // amiből kettő egész szám.
            'latitude' => [
                "decimal:" . APP__DEC_LENGTHS . "," . APP_DEC_DIGITS
            ],
            // A város hosszúsága tizedesjegy, legfeljebb 10 digit hosszú, 
            // amiből kettő egész szám.
            'longitute' => [
                "decimal:" . APP__DEC_LENGTHS . "," . APP_DEC_DIGITS
            ],
            // A várost aktívként vagy inaktívként kell megjelölni.
            'active' => ['required', 'boolean'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'name' => [
                'required' => __('validate_required'),
                'string' => __('validate_string'),
                'min' => __('validate_min.numeric'),
                'max' => __('validate_max.numeric'),
                'unique' => __('validate_unique'),
            ],
            'country_id' => [
                'required' => __('validate_required'),
                'integer' => __('validate_integer'),
            ],
            'region_id' => [
                'required' => __('validate_required'),
                'integer' => __('validate_integer'),
            ],
            'latitude' => [
                'decimal' => __('validate_decimal', ['decimal' => (APP_DEC_LENGTHS - APP_DEC_DIGITS)]),
            ],
            'longitude' => [
                'decimal' => __('validate_decimal', ['decimal' => (APP_DEC_LENGTHS - APP_DEC_DIGITS)]),
            ],
            'active' => [
                'required' => __('validate_required'),
                'boolean' => __('validate_boolean'),
            ],
        ];
    }
    
}
