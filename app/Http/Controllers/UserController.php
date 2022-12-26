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
     * Shows the user profile of the user identified by the argument id.
     * 
     * @param int $id the id of the user to show
     */
    public function show(Request $request, User $user) {
        $this->authorize('view', $user);

        return $request->wantsJson()
            ? new JsonResponse($user)
            : view('pages.profile', ['user' => $user]);
    }

    /**
     * Register a new user.
     * This endpoint is API only. 
     *
     * @return User The user registered.
     */
    public function store(Request $request) {

        $requestData = $request->all();

        $this->userCreationValidator($requestData)->validate();

        $user = $this->storeUser($requestData);

        return new JsonResponse($user->toArray(), 201);
    }

    public function storeUser(array $data) {
        $user = new User();

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = $data['password'];
        $user->is_blocked = $data['is_blocked'] ?? FALSE;
        $user->is_admin = $data['is_admin'] ?? FALSE;
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
            ],
            'is_blocked' => 'boolean',
            'is_admin' => 'boolean'
        ]);
    }

    public function update(Request $request, User $user) {

        $requestData = $request->all();

        $this->userEditionValidator($requestData)->validate();

        $this->authorize('update', $user);

        $user = $this->updateUser($user, $requestData);

        return $request->wantsJson()
            ? new JsonResponse($user->toArray(), 201)
            : redirect()->route('user.profile', ['user' => $user]);
    }

    public function updateUser(User $user, array $data) {
        if (($data['name'] ??= null) !== null)
            $user->name = $data['name'];

        if (isset($data['profile_picture'])) {
            Files::convertToWebp($data['profile_picture'], 512, 1);

            $path = Storage::putFileAs("public/users/", $data['profile_picture'], "$user->id.webp");

            if ($path === false) {
                // TODO: handle file upload err
            }
        }

        if (($data['is_admin'] ??= null) !== null)
            $user->is_admin = $data['is_admin'];

        if (($data['is_blocked'] ??= null) !== null)
            $user->is_blocked = $data['is_blocked'];

        $user->save();

        return $user;
    }

    protected function userEditionValidator(array $data) {
      return Validator::make($data, [
          'name' => 'string|min:6|max:255',
          'profile_picture' => [
            File::image()
              ->max(5*1024)
          ],
          'is_blocked' => 'boolean',
          'is_admin' => 'boolean'
      ]);
    }

    public function edit(User $user) { 
        $this->authorize('showProfileEditPage', $user);

        return view('pages.profile.edit', ['user' => $user]);
    }

    public function destroy(Request $request, User $user) {
        $this->authorize('delete', $user);
        
        $user->delete();

        return $request->wantsJson()
            ? new JsonResponse($user->toArray(), 200)
            : redirect()->route('home');
    }
}