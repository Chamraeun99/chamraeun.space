<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\SupabaseStorage;
use Cloudinary\Cloudinary;

class UserResource extends JsonResource
{
    /**
     * Avatar column may hold a remote URL (OAuth) or a storage key; avoid throwing on /auth/me.
     */
    private function resolveAvatarUrl(): ?string
    {
        $avatar = $this->avatar;
        if (! is_string($avatar) || $avatar === '') {
            return null;
        }

        if (preg_match('/^https?:\/\//i', $avatar)) {
            return $avatar;
        }

        if ($this->avatar_disk === 'cloudinary') {
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

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => $this->resolveAvatarUrl(),
            'bio' => $this->bio,
            'github_url' => $this->github_url,
            'linkedin_url' => $this->linkedin_url,
            'is_active' => $this->is_active,
            'role' => $this->role ? [
                'id' => $this->role->id,
                'name' => $this->role->name,
                'display_name' => $this->role->display_name,
            ] : null,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
