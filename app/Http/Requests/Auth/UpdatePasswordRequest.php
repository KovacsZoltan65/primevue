<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdatePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'password' => [
                'required', 'confirmed', 
                Password::min(APP_PASSWORD_MIN_LENGTHS)
                ->letters()->mixedCase()->numbers()->symbols()
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
                ->uncompromised(), 
                function($attribute, $value, $fail): void {
                    // Ellenőrzi, hogy a jelszó tartalmaz-e legalább 2 számot
                    if (preg_match_all('/[0-9]/', $value) < APP_PASSWORD_MIN_NUMBERS) {
                        $fail('The :attribute must contain at least 2 numbers.');
                    }
                    // Ellenőrzi, hogy a jelszó tartalmaz-e legalább 3 betűt
                    if (preg_match_all('/[a-zA-Z]/', $value) < APP_PASSWORD_MIN_LETTERS) {
                        $fail('The :attribute must contain at least 3 letters.');
                    }
                }
            ],

            // A 'password_confirmation' mezőt a 'confirmed' szabály kezeli automatikusan
        ];
    }
}
