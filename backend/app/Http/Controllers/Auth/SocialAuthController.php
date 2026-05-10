<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * When FRONTEND_URL is missing, fall back safely. Uses config — not raw env — so redirects work after `php artisan config:cache`.
     */
    private function frontendBaseUrl(): string
    {
        $configured = config('frontend.url', '');
        if (is_string($configured) && $configured !== '') {
            return rtrim($configured, '/');
        }

        return rtrim(app()->environment('local')
            ? 'http://localhost:3000'
            : 'https://chamraeun-space-frontend.onrender.com', '/');
    }

    private function memberRole(): Role
    {
        return Role::firstOrCreate(
            ['name' => 'member'],
            [
                'display_name' => 'Member',
                'description' => 'Registered user with basic access',
            ]
        );
    }

    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            Log::error('Google social login failed', [
                'message' => $e->getMessage(),
            ]);
            return redirect($this->frontendBaseUrl() . '/auth/login?error=google_auth_failed');
        }

        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            // Update google_id if user exists by email but hasn't linked Google yet
            if (!$user->google_id) {
                $user->update(['google_id' => $googleUser->getId()]);
            }
            // Update avatar from Google if user doesn't have one
            if (!$user->avatar && $googleUser->getAvatar()) {
                $user->update(['avatar' => $googleUser->getAvatar()]);
            }
        } else {
            // Create new user (opaque password matches GitHub path; avoids NOT NULL DB errors if migrations lag)
            $memberRole = $this->memberRole();
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => Hash::make(Str::random(40)),
                'role_id' => $memberRole->id,
            ]);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return redirect($this->frontendBaseUrl() . '/auth/google/callback?token=' . rawurlencode($token));
    }

    public function redirectToGithub(): RedirectResponse
    {
        return Socialite::driver('github')
            ->scopes(['user:email'])
            ->stateless()
            ->redirect();
    }

    public function handleGithubCallback(): RedirectResponse
    {
        try {
            $githubUser = Socialite::driver('github')->scopes(['user:email'])->stateless()->user();
        } catch (\Exception $e) {
            Log::error('GitHub social login failed', [
                'message' => $e->getMessage(),
            ]);
            return redirect($this->frontendBaseUrl() . '/auth/login?error=github_auth_failed');
        }

        $email = $githubUser->getEmail();
        $user = User::where('github_id', $githubUser->getId())
            ->when($email, fn ($q) => $q->orWhere('email', $email))
            ->first();

        if ($user) {
            if (!$user->github_id) {
                $user->update(['github_id' => $githubUser->getId()]);
            }
            if (!$user->avatar && $githubUser->getAvatar()) {
                $user->update(['avatar' => $githubUser->getAvatar()]);
            }
        } else {
            $memberRole = $this->memberRole();
            $fallbackEmail = 'github_' . $githubUser->getId() . '@users.noreply.github.com';
            $user = User::create([
                'name' => $githubUser->getName() ?? $githubUser->getNickname(),
                'email' => $email ?: $fallbackEmail,
                'github_id' => $githubUser->getId(),
                'avatar' => $githubUser->getAvatar(),
                // Keep compatibility with schemas that still require password.
                'password' => Hash::make(Str::random(40)),
                'role_id' => $memberRole->id,
            ]);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return redirect($this->frontendBaseUrl() . '/auth/github/callback?token=' . rawurlencode($token));
    }
}
