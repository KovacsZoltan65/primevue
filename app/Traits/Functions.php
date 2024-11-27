<?php

namespace App\Traits;

trait Functions
{
    public function getValidationRules()
    {
        //
        $rules = json_decode(file_get_contents(resource_path('js/Validation/ValidationRules.json')), true);
        
        return $rules;
    }
}
