<?php

namespace App\Http\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'request', 'string', 
                "min:" . APP_MIN_STRING_LENGTH, 
                "max:" . APP_MAX_STRING_LENGTH
            ],
            'password' => [
                'request', 'confirmed', 
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                /**
                 * A Laravelben a uncompromised() szabály a jelszavak biztonságának ellenőrzésére szolgál. 
                 * Ez a szabály ellenőrzi, hogy a felhasználó által megadott jelszó nem került-e már 
                 * nyilvánosságra korábbi adatlopások vagy biztonsági incidensek során.
                 * Ez a funkció a Have I Been Pwned (https://haveibeenpwned.com/) adatbázisával kommunikál, 
                 * amely hatalmas mennyiségű, kompromittálódott jelszót tartalmaz. 
                 * Amikor a uncompromised() szabályt alkalmazod, Laravel lekérdezi ezt az adatbázist, 
                 * és ha a megadott jelszó szerepel a kompromittálódott jelszavak között, 
                 * akkor a validáció sikertelen lesz.
                 * Testreszabás:
                 * Megadhatsz egy paramétert is a uncompromised() metódusnak, amely meghatározza, 
                 * hogy hány találat után tekintse a jelszót kompromittálódottnak. 
                 * Például, ha legalább 5 alkalommal jelent meg egy jelszó az adatbázisban, 
                 * akkor kompromittáltnak tekinthető.
                 * Miért fontos:
                 * A uncompromised() szabály hozzáadása jelentősen növeli az alkalmazás biztonságát, 
                 * mivel sok felhasználó hajlamos ismert és gyenge jelszavakat használni. 
                 * Ez a funkció segít megakadályozni, hogy felhasználók olyan jelszót válasszanak, 
                 * amely korábban már kompromittálódott, így csökkenti a biztonsági kockázatokat.
                 */
                    ->uncompromised()   // Ellenőrzi, hogy a jelszó nem kompromittálódott-e
            ],
            // A 'password_confirmation' mezőt a 'confirmed' szabály kezeli automatikusan
            
            'password_2' => \Illuminate\Validation\Rule::password()
                ->min(8)->mixedCase()->symbols(),
                function($attribute, $value, $fail){
                    // Ellenőrzi, hogy a jelszó tartalmaz-e legalább 2 számot
                    if (preg_match_all('/[0-9]/', $value) < 2) {
                        $fail('The :attribute must contain at least 2 numbers.');
                    }
                    
                    // Ellenőrzi, hogy a jelszó tartalmaz-e legalább 3 betűt
                    if (preg_match_all('/[a-zA-Z]/', $value) < 3) {
                        $fail('The :attribute must contain at least 3 letters.');
                    }
                }
        ];
    }
}
