<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesSupabaseCoverUploads;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\ActivityLog;
use App\Models\Project;
use App\Services\SupabaseStorage;
use Cloudinary\Cloudinary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    use HandlesSupabaseCoverUploads;

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $query = Project::with(['tags', 'creator']);

        if ($user && !$user->isSuperAdmin()) {
            $query->where('created_by', $user->id);
        }

        if ($search = $request->get('search')) {
            $query->where('title', 'ilike', "%{$search}%");
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $projects = $query->orderByDesc('created_at')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => ProjectResource::collection($projects),
            'meta' => [
                'current_page' => $projects->currentPage(),
                'last_page' => $projects->lastPage(),
                'per_page' => $projects->perPage(),
                'total' => $projects->total(),
            ],
        ]);
    }

    public function store(ProjectRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            $provider = $request->input('storage_provider', 'supabase');
            $err = $this->validateCoverStorageOrFail($provider);
            if ($err) {
                return $err;
            }
            try {
                $data['cover_image'] = $this->uploadCoverImage($request->file('cover_image'), $provider);
                $data['storage_provider'] = $provider;
            } catch (\Throwable $e) {
                report($e);

                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage() ?: 'Cover image upload failed.',
                ], 422);
            }
        }

        $data['created_by'] = auth()->id();
        $tags = $data['tags'] ?? [];
        unset($data['tags']);

        $project = Project::create($data);
        if ($tags) {
            $project->tags()->sync($tags);
        }

        ActivityLog::log('created', "Created project: {$project->title}", $project);

        return response()->json([
            'success' => true,
            'data' => new ProjectResource($project->load(['tags', 'creator'])),
            'message' => 'Project created successfully.',
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $project = Project::with(['tags', 'creator'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => new ProjectResource($project),
        ]);
    }

    public function update(ProjectRequest $request, int $id): JsonResponse
    {
        $project = Project::findOrFail($id);
        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            $this->deleteCoverImage($project->cover_image, $project->storage_provider ?? 'supabase');
            $provider = $request->input('storage_provider', 'supabase');
            $err = $this->validateCoverStorageOrFail($provider);
            if ($err) {
                return $err;
            }
            try {
                $data['cover_image'] = $this->uploadCoverImage($request->file('cover_image'), $provider);
                $data['storage_provider'] = $provider;
            } catch (\Throwable $e) {
                report($e);

                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage() ?: 'Cover image upload failed.',
                ], 422);
            }
        }

        $tags = $data['tags'] ?? [];
        unset($data['tags']);

        $project->update($data);
        if (isset($request->tags)) {
            $project->tags()->sync($tags);
        }

        ActivityLog::log('updated', "Updated project: {$project->title}", $project);

        return response()->json([
            'success' => true,
            'data' => new ProjectResource($project->fresh()->load(['tags', 'creator'])),
            'message' => 'Project updated successfully.',
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $project = Project::findOrFail($id);
        $title = $project->title;
        $project->delete();

        ActivityLog::log('deleted', "Deleted project: {$title}");

        return response()->json([
            'success' => true,
            'message' => 'Project deleted successfully.',
        ]);
    }

    // ─── Storage Helpers ─────────────────────────────────────

    private function cloudinary(): Cloudinary
    {
        return new Cloudinary(config('cloudinary.cloud_url'));
    }

    private function uploadCoverImage($file, string $provider): string
    {
        if ($provider === 'cloudinary') {
            $result = $this->cloudinary()->uploadApi()->upload($file->getRealPath(), [
                'folder' => 'kalapak/projects',
                'resource_type' => 'image',
            ]);
            return $result['secure_url'];
        }

        return app(SupabaseStorage::class)->upload($file, 'projects');
    }

    private function deleteCoverImage(?string $url, ?string $provider): void
    {
        if (!$url)
            return;

        try {
            if ($provider === 'cloudinary') {
                if (preg_match('/upload\/(?:v\d+\/)?(.+)\.\w+$/', $url, $m)) {
                    $this->cloudinary()->uploadApi()->destroy($m[1]);
                }
            } else {
                app(SupabaseStorage::class)->delete($url);
            }
        } catch (\Throwable $e) {
            \Log::warning("Failed to delete project cover image: {$e->getMessage()}");
        }
    }
}
