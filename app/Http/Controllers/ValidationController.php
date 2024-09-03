<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ValidationController extends Controller
{
    public function validation_01(){
        return Inertia::render('validation/Vuelidate_01');
    }

    public function validation_02(){
        return Inertia::render('validation/Vuelidate');
    }
}
