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
                "min:{$this->string_length_min}", 
                "max:{$this->string_length_max}", 
                Rule::unique('cities', 'name')->ignore($this->id),
            ],
            // Az ország azonosítójának jelen kell lennie, és egész számnak kell lennie.
            'country_id' => ['required', 'integer'],
            // A régió azonosítójának jelen kell lennie, és egész számnak kell lennie.
            'region_id' => ['required', 'integer'],
            // A város szélessége tizedesjegy, legfeljebb 10 digit hosszú, 
            // amiből kettő egész szám.
            'latitude' => [
                "decimal:{$this->dec_length},{$this->dec_digit}"
            ],
            // A város hosszúsága tizedesjegy, legfeljebb 10 digit hosszú, 
            // amiből kettő egész szám.
            'longitute' => [
                "decimal:{$this->dec_length},{$this->dec_digit}"
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
                'min' => __('validate_min.numeric', ['min' => $this->string_length_min]),
                'max' => __('validate_max.numeric', ['max' => $this->string_length_max]),
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
                'decimal' => __('validate_decimal', ['decimal' => ($this->dec_length - $this->dec_digit)]),
            ],
            'longitude' => [
                'decimal' => __('validate_decimal', ['decimal' => ($this->dec_length - $this->dec_digit)]),
            ],
            'active' => [
                'required' => __('validate_required'),
                'boolean' => __('validate_boolean'),
            ],
        ];
    }
    
}
