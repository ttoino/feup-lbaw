<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticController extends Controller {
    public const STATIC_PAGES = ['about', 'contacts', 'faq', 'services'];

    public function show(Request $request) {
        return view('static.' . $request->path());
    }
}