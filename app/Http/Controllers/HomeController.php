<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller {
    public function show() {
        if (Auth::user()->is_admin)
            return redirect(route('admin'));

        if (Auth::user())
            return redirect(route('project.list'));

        return view('pages.home');
    }
}