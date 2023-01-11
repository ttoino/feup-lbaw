<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller {

    const PROVIDERS = [
        'github',
        'google'
    ];

    public function redirectOAuth(Request $request) {
        $provider = $request->route('provider');

        return Socialite::driver($provider)->redirect();
    }

    public function handleOAuthCallback(Request $request) {

        $provider = $request->route('provider');

        $user = Socialite::driver($provider)->stateless()->user();

        dd($user);
    }
}