<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientErrorController extends Controller
{
    public function store(Request $request)
    {
        //\Log::info('ClientErrorController@store $request->all(): ' . print_r($request->all(), true));
        $validated = $request->validate([
            'message' => 'required|string',
            'stack' => 'nullable|string',
            'component' => 'nullable|string',
            'info' => 'nullable|string',
            'time' => 'required|date',
        ]);

        activity()
            ->causeBy(auth()->user())
            ->withProperties($validated)
            ->log('Client-side error reported.');

            return response()
                ->json(['message' => 'Error logged successfully.', ], 201);
    }
}
