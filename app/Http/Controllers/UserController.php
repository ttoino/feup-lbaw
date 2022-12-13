<?php

namespace App\Http\Controllers;

use App\Helpers\Files;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rules\File;

use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller {

    /**
     * Shows the user profile of the user identified by the argument id
     * 
     * @param int $id the id of the user to show
     */
    public function show(User $user) {
        $this->authorize('view', $user);

        return view('pages.profile', ['user' => $user]);
    }

    /**
     * Shows users by project.
     *
     * @return Response
     */
    public function list($id) {
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
            'email' => 'required|string|email|unique:user_profile',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
            ]
        ]);
    }

    public function edit(Request $request, User $user) {

        $requestData = $request->all();

        $this->userEditionValidator($requestData)->validate();

        $this->authorize('update', $user);

        $user = $this->editUser($user, $requestData);

        return $request->wantsJson()
            ? new JsonResponse($user->toArray(), 201)
            : redirect()->route('user.profile', ['user' => $user]);
    }

    public function editUser(User $user, array $data) {
        if ($data['name'] !== null)
            $user->name = $data['name'];

        if (isset($data['profile_picture'])) {
            Files::convertToWebp($data['profile_picture'], 512, 1);

            $path = Storage::putFileAs("public/users/", $data['profile_picture'], "$user->id.webp");

            if ($path === false) {
                // TODO: handle file upload err
            }
        }

        $user->save();

        return $user;
    }

    protected function userEditionValidator(array $data) {
      return Validator::make($data, [
          'name' => 'string|min:6|max:255',
          'profile_picture' => [
            File::image()
              ->max(5*1024)
          ]
      ]);
    }

    public function showProfileEditPage($id) {
        $user = User::findOrFail($id);

        $this->authorize('showProfileEditPage', $user);

        return view('pages.profile.edit', ['user' => $user]);
    }

    public function delete(Request $request, User $user) {
        $this->authorize('delete', $user);
        $user->delete();

        return $request->wantsJson()
            ? new JsonResponse($user->toArray(), 200)
            : redirect()->route('home');
    }
}