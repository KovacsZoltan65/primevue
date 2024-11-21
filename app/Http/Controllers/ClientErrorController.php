<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClientErrorController extends Controller
{
    public function store(Request $request)
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
