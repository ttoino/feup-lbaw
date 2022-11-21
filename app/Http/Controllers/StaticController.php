<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticController extends Controller {
    public function show(Request $request) {
        return view('static.' . $request->path());
    }
}