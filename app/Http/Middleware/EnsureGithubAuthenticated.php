<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureGithubAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->has('github_user')) {
            $request->session()->put('github_intended', $request->fullUrl());

            return redirect()->route('github.redirect');
        }

        $allowedUsername = config('app.github_allowed_username');
        $githubUser = $request->session()->get('github_user', []);
        $githubNickname = (string) ($githubUser['nickname'] ?? '');

        if ($allowedUsername && strcasecmp($githubNickname, $allowedUsername) !== 0) {
            $request->session()->forget('github_user');
            $request->session()->forget('github_intended');

            return redirect()
                ->route('home')
                ->with('error', 'Your GitHub account is not authorized.');
        }

        return $next($request);
    }
}
