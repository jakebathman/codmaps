<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class GithubAuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('github')
            ->scopes(['read:user', 'user:email'])
            ->redirect();
    }

    public function callback(Request $request): RedirectResponse
    {
        $githubUser = Socialite::driver('github')->user();
        $allowedUsername = config('app.github_allowed_username');
        $githubNickname = (string) $githubUser->getNickname();

        if ($allowedUsername && strcasecmp($githubNickname, $allowedUsername) !== 0) {
            $request->session()->forget('github_user');
            $request->session()->forget('github_intended');

            return redirect()
                ->route('home')
                ->with('error', 'Your GitHub account is not authorized.');
        }

        $request->session()->put('github_user', [
            'id' => $githubUser->getId(),
            'nickname' => $githubNickname,
            'name' => $githubUser->getName(),
            'email' => $githubUser->getEmail(),
            'avatar' => $githubUser->getAvatar(),
        ]);

        $intended = $request->session()->pull('github_intended', route('maps'));

        return redirect()->to($intended);
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('github_user');
        $request->session()->forget('github_intended');

        return redirect()->route('home');
    }
}
