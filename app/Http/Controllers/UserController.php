<?php

namespace App\Http\Controllers; 

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

use App\Models\User;
use App\Models\Project;



class UserController extends Controller {

    /**
     * Shows the user profile of the user identified by the argument id
     * 
     * @param int $id the id of the user to show
     */
    public function show(int $id) {
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
        $users = Project::findOrFail($id)->users()->orderBy('id')->get();
        return view('', ['users' => $users]);
    }

    /**
     * Register a new user.
     *
     * @return User The user registered.
     */
    public function create(Request $request) {
        
      $requestData = $request->all();

      $this->userCreationValidator($requestData)->validate();

      //$this->authorize('create', $user);

      $user = $this->createProfile($requestData);

      return $request->wantsJson()
        ? new JsonResponse($user->toArray(), 201)
        : redirect()->route('user.profile', ['id' => $user->id]);
    }

    public function createUser(array $data) {
      $user = new User();

      $user->name = $data['name'];
      $user->email = $data['email'];
      $user->password = $data['password'];
      $user->is_blocked = FALSE;
      $user->save();

      return $user;
    }

    public function userCreationValidator(array $data) {
      return Validator::make($data, [
        'name' => 'required|string|min:6|max:255',
        'email' => 'required|string|email',
        'password' => [
          'required', 
          'confirmed', 
          Password::min(8)
            ->letters()
            ->uncompromised(3)
        ]
      ]);
    }

    public function edit(Request $request, int $id) {

      $requestData = $request->all();

      $this->userEditionValidator($requestData)->validate();

      // TODO: implement policies

      $user = $this->editUser($id, $requestData);
      
      return $request->wantsJson()
        ? new JsonResponse($user->toArray(), 201)
        : redirect()->route('user.profile', ['id' => $user->id]);
    }

    public function editUser(int $id, array $data) {
      $user = User::findOrFail($id);

      if ($data['name'] !== null) $user->name = $data['name'];
      
      $user->save();

      return $user;
    }

    protected function userEditionValidator(array $data) {
      return Validator::make($data, [
          'name' => 'string|min:6|max:255',
      ]);
    }
    
    public function showProfileEditPage($id) {
      $user = User::findOrFail($id);
      
      return view('pages.profile.edit', ['user' => $user]);
    }

    public function delete(Request $request, int $id){
        $user = User::findOrFail($id);
  
        $this->authorize('delete', $user);
        $user->delete();
  
        return $request->wantsJson()
          ? new JsonResponse($user->toArray(), 200)
          : redirect()->route('home');
    } 
}
