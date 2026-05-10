<?php

namespace App\Http\Resources;

use App\Services\SupabaseStorage;
use App\Support\AvatarUrlResolver;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogPostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'cover_image' => $this->coverImageUrl(),
            'storage_provider' => $this->storage_provider ?? 'supabase',
            'status' => $this->status,
            'is_featured' => $this->is_featured,
            'views_count' => $this->views_count,
            'reading_time' => $this->reading_time,
            'published_at' => $this->published_at?->toISOString(),
            'author' => $this->whenLoaded('author', fn () => [
                'id' => $this->author->id,
                'name' => $this->author->name,
                'avatar' => AvatarUrlResolver::forUser($this->author),
                'bio' => $this->author->bio,
            ]),
            'category' => $this->whenLoaded('category', fn() => [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
                'color' => $this->category->color,
            ]),
            'comments_count' => $this->whenCounted('approvedComments'),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }

    private function coverImageUrl(): ?string
    {
        if (!$this->cover_image) {
            return null;
        }

        // Cloudinary URLs are already absolute
        if (($this->storage_provider ?? 'supabase') === 'cloudinary') {
            return $this->cover_image;
        }

        return app(SupabaseStorage::class)->url($this->cover_image);
    }
}
