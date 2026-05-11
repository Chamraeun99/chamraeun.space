<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Services\SupabaseStorage;
use Cloudinary\Cloudinary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Media::with('uploader')
            ->orderByDesc('created_at');

        // Regular admins only see their own uploads; superadmins see all
        $user = $request->user();
        if ($user && $user->isAdmin() && !$user->isSuperAdmin()) {
            $query->where('uploaded_by', $user->id);
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('original_name', 'ilike', "%{$search}%");
        }

        if ($request->filled('type')) {
            $type = $request->input('type');
            if ($type === 'image') {
                $query->where('mime_type', 'like', 'image/%');
            } elseif ($type === 'document') {
                $query->where('mime_type', 'not like', 'image/%');
            }
        }

        $media = $query->paginate($request->input('per_page', 24));

        $items = collect($media->items())->map(function ($item) {
            $data = $item->toArray();
            $data['url'] = $this->getMediaUrl($item);
            if ($item->uploader) {
                $data['uploader'] = [
                    'id' => $item->uploader->id,
                    'name' => $item->uploader->name,
                ];
            }
            return $data;
        });

        return response()->json([
            'success' => true,
            'data' => $items,
            'meta' => [
                'current_page' => $media->currentPage(),
                'last_page' => $media->lastPage(),
                'per_page' => $media->perPage(),
                'total' => $media->total(),
            ],
        ]);
    }

    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'max:51200', 'mimes:jpg,jpeg,png,webp,gif,pdf,mp4,mov,avi,webm'],
            'storage_provider' => ['nullable', 'in:supabase,cloudinary'],
        ]);

        try {
            $file = $request->file('file');
            $requestedProvider = $request->input('storage_provider');

            $supabase = app(SupabaseStorage::class);
            $supabaseConfigured = $supabase->isConfigured();
            $cloudinaryConfigured = !empty((string) config('cloudinary.cloud_url'));

            $provider = $requestedProvider ?: ($supabaseConfigured ? 'supabase' : ($cloudinaryConfigured ? 'cloudinary' : 'supabase'));

            if ($provider === 'supabase' && !$supabaseConfigured) {
                return response()->json([
                    'success' => false,
                    'message' => 'Supabase storage is not configured. Switch storage provider to Cloudinary or set SUPABASE_URL and SUPABASE_SECRET_KEY on the backend.',
                ], 422);
            }
            if ($provider === 'cloudinary' && !$cloudinaryConfigured) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cloudinary is not configured. Set CLOUDINARY_URL (or CLOUDINARY_CLOUD_NAME + CLOUDINARY_API_KEY + CLOUDINARY_API_SECRET) on the backend.',
                ], 422);
            }

            if ($provider === 'cloudinary') {
                $result = $this->cloudinary()->uploadApi()->upload($file->getRealPath(), [
                    'folder' => 'kalapak/media',
                    'resource_type' => 'auto',
                ]);
                $path = $result['public_id'];
                $url = $result['secure_url'];
            } else {
                $path = $supabase->upload($file, 'media');
                $url = $supabase->url($path);
            }

            $media = Media::create([
                'filename' => basename($path),
                'original_name' => $file->getClientOriginalName(),
                'path' => $path,
                'disk' => $provider,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'uploaded_by' => $request->user()?->id,
            ]);

            $responseData = $media->toArray();
            $responseData['url'] = $url;

            return response()->json([
                'success' => true,
                'data' => $responseData,
                'message' => 'File uploaded successfully.',
            ], 201);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Media upload failed', [
                'error' => $e->getMessage(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Storage error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $media = Media::findOrFail($id);

        if ($media->disk === 'cloudinary') {
            try {
                $this->cloudinary()->uploadApi()->destroy($media->path);
            } catch (\Exception $e) {
                // log but don't block deletion
            }
        } else {
            app(SupabaseStorage::class)->delete($media->path);
        }

        $media->delete();

        return response()->json([
            'success' => true,
            'message' => 'Media deleted successfully.',
        ]);
    }

    private function cloudinary(): Cloudinary
    {
        return new Cloudinary(config('cloudinary.cloud_url'));
    }

    private function getMediaUrl(Media $item): string
    {
        if ($item->disk === 'cloudinary') {
            return $this->cloudinary()->image($item->path)->toUrl();
        }

        return app(SupabaseStorage::class)->url($item->path) ?? '';
    }
}
