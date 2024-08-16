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

    protected function isLocaleSupported($locale)
    {
        return in_array($locale, config('app.supported_locales', []));
    }
}
