<?php

namespace App\Http\Controllers;

use App\Helpers\Files;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rules\File;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\RequestStack;

class UserController extends Controller {

    /**
     * Shows the user profile of the user identified by the argument id.
     * 
     * @param int $id the id of the user to show
     */
    public function show(Request $request, User $user) {
        $this->authorize('view', $user);

        return $request->wantsJson()
            ? response()->json($user)
            : response()->view('pages.profile', ['user' => $user]);
    }

    public function showNotifications(Request $request) {

        $user = $request->user();

        $notifications = $user->unreadNotifications()->cursorPaginate(10);

        return $request->wantsJson()
            ? response()->json($notifications)
            : response()->view('pages.notifications', ['notifications' => $notifications]);
    }

    /**
     * Register a new user.
     * This endpoint is API only. 
     *
     * @return User The user registered.
     */
    public function store(Request $request) {

        $this->userCreationValidator($request)->validate();

        $user = $this->storeUser($request);

        return response()->json($user->toArray(), 201);
    }

    public function storeUser(Request $request) {
        
        $data = $request->all();
        
        $user = new User();

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = $data['password'];
        $user->is_blocked = $data['is_blocked'] ?? FALSE;
        $user->is_admin = $data['is_admin'] ?? FALSE;
        $user->save();

        return $user;
    }

    public function userCreationValidator(Request $request) {
        return Validator::make($request->all(), [
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

        $this->userEditionValidator($request)->validate();

        $this->authorize('update', $user);

        $user = $this->updateUser($user, $request);

        return $request->wantsJson()
            ? response()->json($user->toArray(), 201)
            : redirect()->route('user.profile', ['user' => $user]);
    }

    public function updateUser(User $user, Request $request) {

        $data = $request->all();

        if (($data['name'] ??= null) !== null)
            $user->name = $data['name'];

        if (isset($data['profile_picture'])) {
            Files::convertToWebp($data['profile_picture'], 512, 1);

            // TODO: change this to use accessor
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

    protected function userEditionValidator(Request $request) {
        return Validator::make($request->all(), [
            'name' => 'string|min:6|max:255',
            'profile_picture' => [
                File::image()
                    ->max(5 * 1024)
            ],
            'is_blocked' => 'boolean',
            'is_admin' => 'boolean'
        ]);
    }

    public function edit(User $user) {
        $this->authorize('showProfileEditPage', $user);

        return response()->view('pages.profile.edit', ['user' => $user]);
    }

    public function block(Request $request, User $user) {
        $this->authorize('block', $user);

        $user->blocked = true;
        $user->save();

        return $request->wantsJson()
            ? response()->json($user->toArray(), 200)
            : redirect()->route('home');
    }

    public function unblock(Request $request, User $user) {
        $this->authorize('unblock', $user);

        $user->blocked = false;
        $user->save();

        return $request->wantsJson()
            ? response()->json($user->toArray(), 200)
            : redirect()->route('home');
    }

    public function showReportForm(User $user) {
        $this->authorize('report', $user);

        return view('pages.reportuser', ['user' => $user]);
    }

    public function report(Request $request, User $user) {
        $this->reportValidator($request);

        $this->authorize('report', $user);

        $report = new Report();

        $report->reason = $request->input('reason');
        $report->user_profile_id = $user->id;
        $report->creator_id = $request->user()->id;
        $report->save();

        return redirect()->route('user.profile', ['user' => $user]);
    }

    protected function reportValidator(Request $request) {
        return Validator::make($request->all(), [
            'reason' => 'string|min:6|max:512'
        ]);
    }

    public function destroy(Request $request, User $user) {
        $this->authorize('delete', $user);

        $user->delete();

        return $request->wantsJson()
            ? response()->json($user->toArray(), 200)
            : redirect()->route('home');
    }
}