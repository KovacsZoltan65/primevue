<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\JsonResponse;

class ErrorController extends Controller
{
    /**
     * Naplóz egy előtérbeli hibaüzenetet és veremkövetést a tevékenységnaplóba.
     *
     * @param Request $request A naplózandó „üzenet” és „verem” adatokat tartalmazó HTTP-kérés.
     * @return \Illuminate\Http\JsonResponse A naplózási állapotot jelző JSON-válasz.
     */
    public function createLog(Request $request): JsonResponse {
        $message = $request->input('message');
        $stack = $request->input('stack');

        activity()
            ->withProperties(['message' => $message, 'stack' => $stack])
            ->log("Frontend error: {$message}");

            return response()->json(['status' => 'error logged'], 200);
    }

    /**
     * Egy adott dátumhoz tartozó frontend-hibaüzeneteket és veremkövetéseket adja vissza JSON formátumban.
     *
     * @param Request $request A dátumot tartalmazó HTTP-kérés.
     * @return \Illuminate\Http\JsonResponse A naplóbejegyzések listája JSON formátumban.
     */
    public function getLogByDate(Request $request): JsonResponse {
        $date = $request->input('date');

        $logs = Activity::whereDate('created_at', $date)->get();
        
        return response()->json($logs, 200);
    }

    /**
     * Egy dátumintervallumon belüli frontend-hibaüzeneteket és veremkövetéseket adja vissza JSON formátumban.
     *
     * @param Request $request A dátumintervallumot tartalmazó HTTP-kérés. A dátumokat a
     *                         `start_date` és `end_date` kulcsokhoz kell megadni.
     * @return \Illuminate\Http\JsonResponse A naplóbejegyzések listája JSON formátumban.
     */
    public function getLogsByDateRange(Request $request): JsonResponse{
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $logs = Activity::whereBetween('created_at', [$startDate, $endDate])->get();

        return response()->json($logs, 200);
    }
}