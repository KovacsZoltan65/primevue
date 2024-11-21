<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;
use Inertia\Response as InertiaResponse;

class ClientErrorController extends Controller
{
    /**
     * ==========================================
     * 1. Hibák listázása dátumszűrővel:
     *      GET /error-logs?date_from=2024-11-01&date_to=2024-11-20
     * 2. Egy adott hiba megtekintése:
     *      GET /error-logs/123
     * 3. Hiba törlése:
     *      DELETE /error-logs/123
     * ==========================================
     * @param Request $request
     * @return type
     */


    /**
     * Sorolja fel az ügyfélhibákat opcionális dátumszűréssel és oldalszámozással.
     *
     * Ez a módszer hibanaplókat kér le az activity_log táblából, szűrve
     * egy opcionális dátumtartomány, amelyet a „date_from” és „date_to” lekérdezések határoznak meg
     * paraméterek. Az eredmények lapozva vannak, az oldalméretet a
     * a 'per_page' lekérdezési paraméter.
     *
     * @param Request $request A HTTP kérés objektum, amely tartalmazhat
     *                         'oldalonként', 'date_from' és 'date_to' lekérdezés
     *                         A lapozás és a szűrés paraméterei.
     * @return \Inertia\Response Az oldalszámozott hibanaplókat tartalmazó tehetetlenségi válasz.
     */
     public function index(Request $request): InertiaResponse
    {
        // Alapértelmezett szűrési paraméterek
        $perPage = $request->get('per_page', 10); // Oldalméret
        $dateFrom = $request->get('date_from'); // Szűrés kezdő dátum
        $dateTo = $request->get('date_to'); // Szűrés végső dátum

        // Alapkérdés az activity_log táblára
        $query = Activity::query()->where('log_name', 'error'); // Csak hibák

        // Dátumtartomány szűrés: ha a date_from paraméter meg van adva, akkor
        // az activity_log táblában a created_at oszlopban azonosított
        // dátumtartományon belül fogja keresni az elemeket.
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        // Dátumtartomány szűrés: ha a date_to paraméter is meg van adva, akkor
        // az activity_log táblában a created_at oszlopban azonosított
        // dátumtartományon belül fogja keresni az elemeket.
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        // Lekérjük a hibákat az activity_log táblából a fenti szűrési paraméterekkel.
        // A ->paginate() metódussal oldalszámozott lista lesz generálva.
        // Az oldalméretet a $perPage változóban megadott érték határozza meg.
        $logs = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return inertia('ErrorLogs/Index', [
            'logs' => $logs,
        ]);
    }

    /**
     * Egy adott hiba megtekintése
     *
     * A show metódus fogja visszaadni a hibát, amelyet az adatbázisból
     * lekérünk a findOrFail() metódussal. Az inertia() függvénnyel egy új oldalt
     * renderelünk a hibával.
     *
     * @param int $id A hiba azonosítója
     * @return \Inertia\Response
     */
    public function show(int $id): InertiaResponse
    {
        // Lekérjük a hibát az adatbázisból
        // A findOrFail() metódus egy kivételt dob, ha a hiba nem található
        $log = Activity::findOrFail($id);

        // Egy hiba megtekintéséhez a show metódus fogja visszaadni a hibát
        // Az inertia() függvénnyel egy új oldalt renderelünk a hibával
        // A hibát egy reaktív hivatkozásban kapjuk meg, amelyet az adatbázisból
        // lekérünk a findOrFail() metódussal.
        return inertia('ErrorLogs/Show', [
            'log' => $log,
        ]);
    }

    /**
     * Törli a megadott hibát az adatbázisból.
     *
     * A destroy metódus fogja visszaadni a hibát, amelyet az adatbázisból
     * lekérünk a findOrFail() metódussal. A hiba törlését a delete() metódus
     * végzi, ami egy "hard delete"-t jelent. A hiba törlését követően a
     * rendszer átirányít a hibalista oldalra, ahol a felhasználó
     * értesülhet a hiba törléséről.
     *
     * @param int $id A hiba azonosítója
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        $log = Activity::findOrFail($id);

        // Példa: a törlés helyett egy "deaktiválás" lehetőséget biztosítunk
        $log->delete(); // Ezt "soft delete"-ként is lehetne implementálni.

        return redirect()->route('error-logs.index')->with('success', 'Log entry deleted.');
    }

    /**
     * Kezeli az ügyféloldali hibajelentést.
     *
     * Ez a függvény naplózza a hibajelentést az activity_log táblába.
     * A hibajelentés JSON-objektumként kerül elküldésre a következő tulajdonságokkal:
     * - info: A hibával kapcsolatos további információkat tartalmazó karakterlánc.
     * - verem: A JavaScript hiba verem nyomkövetését tartalmazó karakterlánc.
     * - komponens: Egy karakterlánc, amely tartalmazza annak az összetevőnek a nevét, ahol a hiba történt.
     * - route: Az aktuális útvonalat tartalmazó karakterlánc.
     * - url: Az aktuális URL-t tartalmazó karakterlánc.
     * - userAgent: A felhasználói ügynököt tartalmazó karakterlánc.
     *
     * A függvény JSON-választ ad vissza 201-es állapotkóddal, jelezve, hogy a hiba sikeresen naplózásra került.
     * A válasz egy üzenetet tartalmaz a hibanapló azonosítójával.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logClientError(Request $request): JsonResponse
    {
        // Lekérjük a hibát a requestből.
        // A hiba egy JSON objektum, amelyet a kliens oldalon
        // a hiba bekövetkeztekor elküldtek.
        $data = $request->all();

        /**
         * A hibát a activity_log táblába rögzítjük.
         * A hiba tulajdonságait a $data változóban megadott
         * tulajdonságokkal fogjuk rögzíteni.
         */
        activity()
            ->withProperties([
                'info' => $data['info'] ?? 'N/A',
                'stack' => $data['stack'] ?? 'N/A',
                'component' => $data['component'] ?? 'N/A',
                'route' => $data['route'] ?? 'N/A',
                'url' => $data['url'] ?? 'N/A',
                'user_agent' => $data['userAgent'] ?? 'N/A',
                'unique_error_id' => Str::uuid()->toString(),
            ])
            ->log('Client-side error reported.');

            // Visszatérési érték: JSON objektum, amely tartalmazza az üzenetet.
            // A 201-es státuszkóddal jelöljük, hogy a hiba sikeresen rögzítésre került.
            // A kliens oldalon ezzel a válasszal tudjuk jelezni, hogy a hiba rögzítésre került.
            return response()->json(['message' => 'Error logged successfully.', ], 201);
    }
}
