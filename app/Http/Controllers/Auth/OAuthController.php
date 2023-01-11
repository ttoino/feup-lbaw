<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use Laravel\Socialite\Facades\Socialite;

use App\Models\OAuthUser;
use App\Models\User;

class OAuthController extends Controller {

    public function redirectOAuth(Request $request) {
        $provider = $request->route('provider');

        return Socialite::driver($provider)->redirect();
    }

    public function handleOAuthCallback(Request $request) {
        $provider = $request->route('provider');

        // FIXME: pretty sure this cannot be stateless but oh well
        $oAuthUser = Socialite::driver($provider)->stateless()->user();

        $user = User::firstWhere('email', $oAuthUser->getEmail());

        if (!$user) { // OAuth Sign Up
            $user = User::create([
                'email' => $oAuthUser->getEmail(),
                'name' => $oAuthUser->getName(),
                'password' => bcrypt(Str::random()), // encrypt in case of data leaks
            ]);
        }

        $userOAuthSignIn = $user->oAuthProfiles
            ->where('provider_type', $provider)
            ->where('provider_token', $oAuthUser->token)
            ->first();

        if (!$userOAuthSignIn) {
            // first time using this provider's OAuth service

            $userOAuthSignIn = OAuthUser::create([
                'provider_type' => $provider,
                'provider_token' => $oAuthUser->token,
                'provider_refresh_token' => $oAuthUser->refresh_token,
                'user_id' => $user->id
            ]);
        } else {
            // check refresh token if needed

        }

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->route('home');
    }
}