<?php

namespace App\Support;

use App\Models\User;
use App\Services\SupabaseStorage;
use Cloudinary\Cloudinary;

/**
 * Resolves a user's avatar for API resources (storage key, OAuth URL, Cloudinary).
 */
final class AvatarUrlResolver
{
    public static function forUser(?User $user): ?string
    {
        if ($user === null) {
            return null;
        }

        $avatar = $user->avatar;
        if (! is_string($avatar) || $avatar === '') {
            return null;
        }

        if (preg_match('/^https?:\/\//i', $avatar)) {
            return $avatar;
        }

        if ($user->avatar_disk === 'cloudinary') {
            try {
                $cloudUrl = config('cloudinary.cloud_url');
                if (! is_string($cloudUrl) || trim($cloudUrl) === '') {
                    return null;
                }

                return (new Cloudinary($cloudUrl))->image($avatar)->toUrl();
            } catch (\Throwable) {
                return null;
            }
        }

        return app(SupabaseStorage::class)->url($avatar);
    }
}
