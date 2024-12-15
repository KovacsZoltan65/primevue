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
    
    /**
     * Használat:
     *  $roles = $this->getUserRoles(['companies', 'users']);
     * @param array|string $str
     * @return array
     */
    public function getUserRoles(array|string $prefix): array
    {
        //$permissions = ['list', 'create', 'edit', 'delete', 'restore'];
        $permissions = config('permission.permissions');
        $prefixes = is_array($prefix) ? $prefix : [$prefix];
        $userRoles = [];
    
        foreach ($prefixes as $str) {
            foreach ($permissions as $permission) {
                $userRoles["{$str}_{$permission}"] = \Auth::user()->can("{$str} {$permission}");
            }
        }
        
        return $userRoles;
    }
}
