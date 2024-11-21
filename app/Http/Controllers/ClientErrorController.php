<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;

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
    public function index(Request $request)
    {
        // Alapértelmezett szűrési paraméterek
        $perPage = $request->get('per_page', 10); // Oldalméret
        $dateFrom = $request->get('date_from'); // Szűrés kezdő dátum
        $dateTo = $request->get('date_to'); // Szűrés végső dátum
        
        // Alapkérdés az activity_log táblára
        $query = Activity::query()->where('log_name', 'error'); // Csak hibák
        
        // Dátumtartomány szűrés
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }
        
        // Lekérdezés végrehajtása oldalszámozással
        $logs = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return inertia('ErrorLogs/Index', [
            'logs' => $logs,
        ]);
    }
    
    public function show(int $id)
    {
        $log = Activity::findOrFail($id);

        return inertia('ErrorLogs/Show', [
            'log' => $log,
        ]);
    }
    
    public function destroy($id)
    {
        $log = Activity::findOrFail($id);

        // Példa: a törlés helyett egy "deaktiválás" lehetőséget biztosítunk
        $log->delete(); // Ezt "soft delete"-ként is lehetne implementálni.

        return redirect()->route('error-logs.index')->with('success', 'Log entry deleted.');
    }
    
    public function logClientError(Request $request)
    {
        $data = $request->all();

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
        /*
        $validated = $request->validate([
            'message' => 'required|string',
            'stack' => 'nullable|string',
            'component' => 'nullable|string',
            'info' => 'nullable|string',
            'time' => 'required|date',
        ]);

        activity()
            ->causedBy(auth()->user())
            ->withProperties($validated)
            ->log('Client-side error reported.');
        */
            return response()
                ->json(['message' => 'Error logged successfully.', ], 201);
    }
}
