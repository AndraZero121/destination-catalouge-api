<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login.login');
    }

    public function register()
    {
        return view('auth.register.regsiter');
    }

    public function logoutView()
    {
        return view('auth.logout.logout');
    }

    public function forgotPassword()
    {
        return view('auth.forgot.forgot');
    }

    public function redirectToProvider(string $provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function handleProviderCallback(string $provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
        } catch (\Exception $e) {
            Log::error("Socialite callback failed", [
                "provider" => $provider,
                "error" => $e->getMessage(),
            ]);
            return redirect("/login?error=social_login_failed");
        }

        $email = $socialUser->getEmail();

        if (!$email) {
            return redirect("/login?error=email_required");
        }

        $user = User::where("email", $email)->first();

        if (!$user) {
            $user = User::create([
                "name" => $socialUser->getName() ?: $socialUser->getNickname() ?: "User",
                "email" => $email,
                "password" => Hash::make(Str::random(32)),
                "email_verified_at" => now(),
                "oauth_provider" => $provider,
                "oauth_id" => $socialUser->getId(),
            ]);
        } else {
            $user->forceFill([
                "oauth_provider" => $user->oauth_provider ?: $provider,
                "oauth_id" => $user->oauth_id ?: $socialUser->getId(),
                "email_verified_at" => $user->email_verified_at ?: now(),
            ])->save();
        }

        $token = $user->createToken("auth_token")->plainTextToken;

        return view("auth.social.callback", [
            "token" => $token,
        ]);
    }
}
