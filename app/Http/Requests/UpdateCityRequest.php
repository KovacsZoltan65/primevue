<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCityRequest extends FormRequest
{
    private int $min_numeric = 2;
    private int $max_numeric = 2;
    private int $dec_digit = 2;
    private int $dec_length = 10;
    private int $string_length_min = 2;
    private int $string_length_max = 255;
    
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
                'request', 'string', 
                "min:" . APP_MIN_STRING_LENGTH, 
                "max:" . APP_MAX_STRING_LENGTH, 
                Rule::unique('cities', 'name')->ignore($this->id),
            ],
            // Az ország azonosítójának jelen kell lennie, és egész számnak kell lennie.
            'country_id' => ['required', 'integer'],
            // A régió azonosítójának jelen kell lennie, és egész számnak kell lennie.
            'region_id' => ['required', 'integer'],
            // A város szélessége tizedesjegy, legfeljebb 10 digit hosszú, 
            // amiből kettő egész szám.
            'latitude' => [
                "decimal:" . APP_DEC_LENGTHS . "," . APP_DEC_DIGITS
            ],
            // A város hosszúsága tizedesjegy, legfeljebb 10 digit hosszú, 
            // amiből kettő egész szám.
            'longitute' => [
                "decimal:" . APP_DEC_LENGTHS . "," . APP_DEC_DIGITS
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
                'min' => __('validate_min.numeric', ['min' => APP_MIN_STRING_LENGTH]),
                'max' => __('validate_max.numeric', ['max' => APP_MAX_STRING_LENGTH]),
                'unique' => _('validate_unique'),
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
