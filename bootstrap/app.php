<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Inertia\Inertia;
use Spatie\Activitylog\Models\Activity;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
            App\Http\Middleware\Localization::class,
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // Adatbázis elérési hiba
        $exceptions->render(function(\PDOException $e) {
            // Ellenőrizzük a hibakódot, hogy részletesebb üzenetet tudjunk adni
            $errorCode = $e->getCode();

            // Adatbázis csatlakozási hibák (például MySQL 2002)
            if ($errorCode === 2002) {
                return Inertia::render('Errors/DatabaseConnectionError');
            }

            // Egyedi üzenet egyéb SQL hibák esetén
            if (in_array($errorCode, [1045, 1049, 1054, 1064])) {
                return Inertia::render('Errors/DatabaseError', [
                    'message' => $e->getMessage(),
                    'error_code' => $errorCode,
                ]);
            }

            // Logolás alternatív módon, ha nincs adatbázis kapcsolat
            try {
                // Spatie log bejegyzés, ha az adatbázis elérhető
                activity()->log('Adatbázis hiba: ' . $e->getMessage());
            } catch(  \Exception $e) {
                // Ha az adatbázis logolás nem lehetséges, akkor fájl logolás
                \Log::error('Adatbázis hiba: ' . $e->getMessage());
            }

            // Általános adatbázis hiba, ha nem ismert a kód
            return Inertia::render('Errors/Database');

        });

        // 404, azaz "Nincs ilyen oldal" hiba
        // Az oldal nem található.
        $exceptions->render(function(NotFoundHttpException $e){

            try {
                // Spatie log bejegyzés, ha az adatbázis elérhető
                activity()->log("Not Found Exception: " . $e->getMessage());
            } catch( \Exception $e) {
                // Ha az adatbázis logolás nem lehetséges, akkor fájl logolás
                \Log::error("Not found Exception: {$e->getMessage()}");
            }

            //
            return Inertia::render('Errors/404');
        });

        // 403, azaz "Forbidden" hiba
        // Amikor egy felhasználó nem rendelkezik jogosultsággal egy adott művelethez.
        $exceptions->render(function(){

            try {
                // Spatie log bejegyzés, ha az adatbázis elérhető
                activity()->log("Forbidden Exception");
            } catch( \Exception $e) {
                // Ha az adatbázis logolás nem lehetséges, akkor fájl logolás
                \Log::error("Forbidden Exception: " . $e->getMessage() );
            }

            //
            return Inertia::render('Errors/403');
        });

        // 419, azaz "Page Expired" hiba
        // A Laravel a bejelentkezés időtúllépését kezeli, de a kliens nem tudja,
        // hogy a bejelentkezésnek megfelelően tartott-e.
        // Ezért a 419 hiba azt jelenti, hogy a kliens nem tudja,
        // a bejelentkezésnek megfelelően tartott-e.
        $exceptions->render(function(){

            try {
                // Spatie log bejegyzés, ha az adatbázis elérhető
                activity()->log("Page Expired Exception");
            } catch( \Exception $e) {
                // Ha az adatbázis logolás nem lehetséges, akkor fájl logolás
                \Log::error("Page Expires Exception: {$e->getMessage()}");
            }

            //
            return Inertia::render('Errors/419');
        });

        // 500, azaz "Internal Server Error" hiba
        // A Laravel hibakezelőben található kivétel.
        // Ezért a 500 hiba azt jelenti, hogy a kiszolgálás nem tudja kezelteni a kérést.
        // A hibakezelőnek kell a hibát bejelenteni a logba, hogy a kliensen is meg lehessen figyelni.
        $exceptions->render(function(){

            try {
                // Spatie log bejegyzés, ha az adatbázis elérhető
                activity()->log("Internal ServerError Exception");
            } catch( \Exception $e) {
                // Ha az adatbázis logolás nem lehetséges, akkor fájl logolás
                \Log::error("Internal Server Error Exception: {$e->getMessage()}");
            }

            //
            return Inertia::render('Errors/500');
        });

        // ValidationException: Adatbeviteli hibák, például formok érvényesítésekor.
        // Az Illuminate\Validation\ValidationException típusát használva,
        // testreszabható üzenetekkel vagy hibák felsorolásával segíthetsz a
        // felhasználónak megérteni, mit szükséges módosítani:
        $exceptions->render(function(\Illuminate\Validation\ValidationException $e){

            try {
                // Spatie log bejegyzés, ha az adatbázis elérhető
                activity()->log("Validation Exception: {$e->getMessage()}");
            } catch( \Exception $e) {
                // Ha az adatbázis logolás nem lehetséges, akkor fájl logolás
                \Log::error("Validation Exception: {$e->getMessage()}");
            }

            //
            return Inertia::render('Errors/Validation', [
                'errors' => $e->errors(),
            ]);
        });
    })->create();
