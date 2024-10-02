<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function index(Request $request)
    {
        $locale = $request->input('locale', config('app.locale'));

        if ($this->isLocaleSupported($locale)) {
            app()->setLocale($locale);

            // Store the locale in the session.
            $request->session()->put('locale', $locale);
        }

        return redirect()->back();
    }

    public function getLanguages(Request $request)
    {
        //$available_locales = config('app.available_locales', ['English' => 'en','Hungarian' => 'hu',]);
        $available_locales = config(
            'app.available_locales', 
            [
                [ 'name' => "United States", 'code' => "US" ],
                [ 'name' => "Magyarország", 'code' => "HU" ],
                [ 'name' => "Great Britain", 'code' => "GB" ],
            ]
        );
        
        $supported_locales = config('app.supported_locales', ['en', 'hu']);
        $locale = ( Session::has('locale') ) ? Session::get('locale') : env('APP_LOCALE');
        
        $ret_val = [
            'available_locales' => $available_locales,
            'supported_locales' => $supported_locales,
            'locale' => $locale,
        ];
        
        return response()->json($ret_val, Response::HTTP_OK);
    }
    
    protected function isLocaleSupported($locale)
    {
        return in_array($locale, config('app.supported_locales', []));
    }
}
