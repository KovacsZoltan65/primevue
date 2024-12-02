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
     * Szerezze be a felhasználó szerepeit és engedélyeit egy adott művelethez.
     *
     * Ez a funkció ellenőrzi, hogy a hitelesített felhasználó rendelkezik-e a megadott jogosultságokkal
     * (listázás, létrehozás, szerkesztés, törlés, visszaállítás) egy adott műveleti karakterlánchoz.
     *
     * @param string $str A műveleti karakterlánc az engedélyek ellenőrzéséhez.
     * @return array Egy asszociatív tömb, ahol a kulcsok engedélyek, az értékek pedig logikai értékek
     *               jelzi, hogy a felhasználó rendelkezik-e minden jogosultsággal.
     */
    public function getUserRoles(string $str): array
    {
        $permissions = ['list', 'create', 'edit', 'delete', 'restore'];
        $userRoles = [];
        foreach ($permissions as $permission) {
            $userRoles[$permission] = \Auth::user()->can("{$str} {$permission}");
        }
        return $userRoles;
    }
}
