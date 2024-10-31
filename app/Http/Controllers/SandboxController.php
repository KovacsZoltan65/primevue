<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class SandboxController extends Controller
{
    public function table_filter_01_index(Request $request)
    {
        return Inertia::render('Sandbox/TableFilter_01');
    }
}
