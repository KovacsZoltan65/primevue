<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class Localization
{
    /**
     * Bejövő kérések kezelése.
     *
     * Amennyiben a session tartalmazza a locale értékét, akkor
     * beállítja az alkalmazásban az adott nyelvet.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ha a session tartalmazza a locale értékét, akkor
        // beállítja az alkalmazásban az adott nyelvet.
        if( Session::has('locale') )
        {
            // A sessionban tárolt nyelvi beállítás alkalmazása
            App::setLocale( Session::get('locale') );
        }
        
        return $next($request);
    }
}
