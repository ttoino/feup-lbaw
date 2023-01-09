<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

use App\Models\User;

class PasswordRecoveryController extends Controller {

    public function showPasswordRecoveryForm() {
        return view('auth.forgot-password');
    }

    public function showPasswordResetForm(Request $request) {
        return view('auth.reset-password', ['token' => $request->route('token')]);
    }

    public function sendPasswordRecoveryLink(Request $request) {

        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );
    
        return $status === Password::RESET_LINK_SENT
                    ? back()->with(['status' => __($status)])
                    : back()->withErrors(['email' => __($status)]);
    }

    public function resetPassword(Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
    
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
                
                $user->save();
    
                event(new PasswordReset($user));
            }
        );
    
        if ($status === Password::PASSWORD_RESET) {
            if (Auth::attempt($request->only('email', 'password'), true)) {
                $request->session()->regenerate();
    
                return redirect()->intended();
            } else return response()->route('home');
        } else return back()->withErrors(['email' => [__($status)]]);
    }
}