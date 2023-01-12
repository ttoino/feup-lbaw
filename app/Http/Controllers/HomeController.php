<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller {
    public function show() {

        $user = Auth::user();

        if ($user === null)
            return response()->view('pages.home');
        else if ($user->is_admin)
            return redirect()->route('admin');
        else if ($user->is_blocked)
            dd('bahhh'); // TODO: implement this
        else        
            return redirect()->route('project.list');
    }
}