<?php

namespace App\Providers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Media;
use App\Models\Project;
use App\Models\Tag;
use App\Models\TeamMember;
use App\Observers\BlogCategoryObserver;
use App\Observers\BlogPostObserver;
use App\Observers\MediaObserver;
use App\Observers\ProjectObserver;
use App\Observers\TagObserver;
use App\Observers\TeamMemberObserver;
use App\Services\SupabaseStorage;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SupabaseStorage::class);
    }

    public function boot(): void
    {
        Project::observe(ProjectObserver::class);
        Tag::observe(TagObserver::class);
        BlogCategory::observe(BlogCategoryObserver::class);
        TeamMember::observe(TeamMemberObserver::class);
        BlogPost::observe(BlogPostObserver::class);
        Media::observe(MediaObserver::class);

        // Override password reset URL to point to frontend (use config, not env — works with config:cache)
        ResetPassword::createUrlUsing(function ($notifiable, string $token) {
            $configured = config('frontend.url', '');
            $frontendUrl = (is_string($configured) && $configured !== '')
                ? rtrim($configured, '/')
                : rtrim(app()->environment('local')
                    ? 'http://localhost:3000'
                    : 'https://chamraeun-space-frontend.onrender.com', '/');

            return $frontendUrl . '/auth/reset-password/' . $token . '?email=' . urlencode($notifiable->getEmailForPasswordReset());
        });

        // General API: 60 requests per minute per IP
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Login/Register: 5 attempts per minute per IP (brute force protection)
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        // Contact/Applications: 3 per minute per IP (spam protection)
        RateLimiter::for('contact', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip());
        });

        // Uploads: 10 per minute per user
        RateLimiter::for('uploads', function (Request $request) {
            return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
        });
    }
}
