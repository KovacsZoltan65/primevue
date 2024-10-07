<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{
    public $validationRules = [];

    public function __construct()
    {
        $this->validationRules = json_decode(file_get_contents(resource_path('js/Validation/ValidationRules.json')), true);
    }
}