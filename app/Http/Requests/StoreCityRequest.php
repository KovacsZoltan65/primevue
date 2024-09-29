<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCityRequest extends FormRequest
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
                "min:{$this->string_length_min}", 
                "max:{$this->string_length_max}",
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
