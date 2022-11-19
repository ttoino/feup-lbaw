<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Project;

class UserController extends Controller {

    public function show($id) {
        $user = User::find($id);
        //$this->authorize('show', $user);
        return view('', ['user' => $user]);
    }

    /**
     * Shows users by project.
     *
     * @return Response
     */
    public function list() {
        if (!Auth::check())
            return redirect('/login');
        //$this->authorize('list', User::class);
        $users = Project::find($id)->users()->orderBy('id')->get();
        return view('', ['users' => $users]);
    }

    /**
     * Register a new user.
     *
     * @return User The user registered.
     */
    public function create(Request $request) {
        $user = new User();

        //$this->authorize('create', $user);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        $user->is_blocked = FALSE;
        $user->save();

        return $user;
    }

    /*
    public function delete(Request $request, $id){
    }
    */
}