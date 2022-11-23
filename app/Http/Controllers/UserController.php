<?php

namespace App\Http\Controllers; 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Project;



class UserController extends Controller {



    public function show($id) {
        $user = User::findOrFail($id);
        //$this->authorize('show', $user);
        return view('pages.profile', ['user' => $user]);
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

    public function edit(Request $request, $id)
    {
      $user = User::find($id);
      if ($request->input('name') == null) $name = $user->name;
      else $name = $request->input('name');
      if ($request->input('email') == null) $email = $user->email;
      else $email = $request->input('email');
      if ($request->input('about') == null) $about = $user->about;
      else $about = $request->input('about');
      $user->name = $name;
      $user->about = $about;
      $user->email = $email;

      $user->save();

      return $user;
    }
    
    public function editPage($id)
    {
      $user = User::find($id);
      return view('pages.edit_profile', ['user' => $user]);
    }
    
    /*
    public function editProfile(Request $request){
          $user = User::find($request->input('user'));
          $user->names = $request->input('name');
          $user->save();
          return redirect('profile/'.$request->input('user'));
        }
    */

    public function delete(Request $request, $id){
        $user2 = User::find($id);
  
        $this->authorize('delete', $user2);
        $user2->delete();
  
        return $user2;
      }
  
    }    