<?php

namespace App\Http\Controllers;

use App\Http\Requests\CentralRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ValidationController extends Controller
{
    public function validation(){
        return Inertia::render('validation/Vuelidate');
    }

    public function validation_01(){
        return Inertia::render('validation/Vuelidate_01');
    }

    public function validation_02(){
        return Inertia::render('validation/Vuelidate_02');
    }

    public function validation_03(Request $request){
        return Inertia::render('validation/Vuelidate_03');
    }

    public function updateVal_03 (CentralRequest $request) {
        \Log::info('updateVal_03' . print_r($request->all(), true));
    }
}
