<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\PublicApi\BlogController;
use App\Http\Controllers\PublicApi\ContactController;
use App\Http\Controllers\PublicApi\HomeController;
use App\Http\Controllers\PublicApi\OgMetaController;
use App\Http\Controllers\PublicApi\ProjectController;
use App\Http\Controllers\Member\ApplicationController;
use App\Http\Controllers\Member\NotificationController;
use App\Http\Controllers\Member\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\ApprovalRequestController;
use App\Http\Controllers\Admin\UserPermissionController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\Admin\StorageStatsController;
use App\Http\Controllers\Admin\DocController;
use App\Http\Controllers\Admin\DocMenuController;
use App\Http\Controllers\PublicApi\DocController as PublicDocController;
use App\Models\Tag;
use App\Models\TeamMember;
use App\Http\Resources\TeamMemberResource;
use Illuminate\Support\Facades\Route;

// ── PUBLIC ROUTES ─────────────────────────────────────

// Auth
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/forgot-password', [PasswordResetController::class, 'forgotPassword'])->middleware('turnstile');
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);
    // Browser OAuth (same path prefix as JSON auth so hosts that only expose /api/* still work)
    Route::get('/google/redirect', [SocialAuthController::class, 'redirectToGoogle']);
    Route::get('/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);
    Route::get('/github/redirect', [SocialAuthController::class, 'redirectToGithub']);
    Route::get('/github/callback', [SocialAuthController::class, 'handleGithubCallback']);
});

// Projects
Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/projects/{slug}', [ProjectController::class, 'show']);

// Blog
Route::get('/blog/posts', [BlogController::class, 'index']);
Route::get('/blog/posts/{slug}', [BlogController::class, 'show']);
Route::get('/blog/categories', [BlogController::class, 'categories']);

// Docs (public)
Route::get('/docs/nav', [PublicDocController::class, 'nav']);
Route::get('/docs', [PublicDocController::class, 'index']);
Route::get('/docs/{slug}', [PublicDocController::class, 'show']);

// OG Meta: Dynamic Open Graph tags for social media crawlers
Route::get('/og/blog/{slug}', [OgMetaController::class, 'blogPost']);

// Team
Route::get('/team', function () {
    $members = TeamMember::where('is_visible', true)->orderBy('display_order')->get();
    return response()->json([
        'success' => true,
        'data' => TeamMemberResource::collection($members),
    ]);
});

// Tags
Route::get('/tags', function () {
    return response()->json([
        'success' => true,
        'data' => Tag::all(),
    ]);
});

// Settings
Route::get('/settings/public', [HomeController::class, 'settings']);

// Contact
Route::post('/contact', [ContactController::class, 'store'])->middleware('turnstile');

// Storage diagnostics (remove after debugging)
Route::get('/storage-test', function () {
    $storage = app(\App\Services\SupabaseStorage::class);
    return response()->json($storage->test());
});

// Applications (public submit)
Route::post('/applications', [ApplicationController::class, 'store'])->middleware('turnstile');

// ── AUTHENTICATED ROUTES ──────────────────────────────

Route::middleware(['auth:sanctum'])->group(function () {
    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    // Upload (for TipTap editor, etc.)
    Route::post('/upload', [MediaController::class, 'upload']);

    // Member routes
    Route::prefix('member')->group(function () {
        Route::get('/profile', [ProfileController::class, 'show']);
        Route::put('/profile', [ProfileController::class, 'update']);
        Route::put('/password', [ProfileController::class, 'updatePassword']);
        Route::post('/avatar', [ProfileController::class, 'uploadAvatar']);

        // Storage provider setting (accessible to all authenticated users)
        Route::get('/storage-settings', function () {
            $providers = \App\Models\Setting::getValue('allowed_storage_providers', 'both');
            return response()->json(['success' => true, 'data' => ['allowed_storage_providers' => $providers]]);
        });

        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
        Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::put('/notifications/read-all', [NotificationController::class, 'markAllRead']);
        Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);
    });

    // ── ADMIN ROUTES ──────────────────────────────────

    Route::prefix('admin')->middleware('admin')->group(function () {
        // Dashboard (admin + superadmin)
        Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
        Route::get('/dashboard/activity', [DashboardController::class, 'activity']);

        // Current user's own permissions
        Route::get('/my-permissions', [UserPermissionController::class, 'mine']);

        // Projects (admin + superadmin)
        Route::get('/projects', [AdminProjectController::class, 'index']);
        Route::post('/projects', [AdminProjectController::class, 'store']);
        Route::get('/projects/{id}', [AdminProjectController::class, 'show']);
        Route::put('/projects/{id}', [AdminProjectController::class, 'update']);
        Route::post('/projects/{id}', [AdminProjectController::class, 'update']);
        Route::delete('/projects/{id}', [AdminProjectController::class, 'destroy']);

        // Blog Posts (admin + superadmin)
        Route::get('/blog/posts', [BlogPostController::class, 'index']);
        Route::post('/blog/posts', [BlogPostController::class, 'store']);
        Route::get('/blog/posts/{id}', [BlogPostController::class, 'show']);
        Route::put('/blog/posts/{id}', [BlogPostController::class, 'update']);
        Route::delete('/blog/posts/{id}', [BlogPostController::class, 'destroy']);
        Route::get('/blog/categories', [BlogPostController::class, 'categories']);
        Route::post('/blog/categories', [BlogPostController::class, 'storeCategory']);
        Route::put('/blog/categories/{id}', [BlogPostController::class, 'updateCategory']);
        Route::delete('/blog/categories/{id}', [BlogPostController::class, 'destroyCategory']);

        // Applications (admin + superadmin)
        Route::get('/applications', [AdminApplicationController::class, 'index']);
        Route::get('/applications/{id}', [AdminApplicationController::class, 'show']);
        Route::put('/applications/{id}/status', [AdminApplicationController::class, 'updateStatus']);

        // Messages (admin + superadmin)
        Route::get('/messages', [MessageController::class, 'index']);
        Route::get('/messages/{id}', [MessageController::class, 'show']);
        Route::put('/messages/{id}/read', [MessageController::class, 'markRead']);
        Route::delete('/messages/{id}', [MessageController::class, 'destroy']);

        // Team Members (admin + superadmin)
        Route::get('/team', [TeamMemberController::class, 'index']);
        Route::post('/team', [TeamMemberController::class, 'store']);
        Route::put('/team/{id}', [TeamMemberController::class, 'update']);
        Route::delete('/team/{id}', [TeamMemberController::class, 'destroy']);

        // Tags (admin + superadmin)
        Route::get('/tags', function () {
            return response()->json(['success' => true, 'data' => Tag::withCount('projects')->orderBy('name')->get()]);
        });
        Route::post('/tags', function (\Illuminate\Http\Request $request) {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'slug' => 'required|string|unique:tags,slug',
                'color' => 'nullable|string|max:7',
            ]);
            return response()->json(['success' => true, 'data' => Tag::create($data)], 201);
        });
        Route::put('/tags/{id}', function (\Illuminate\Http\Request $request, int $id) {
            $tag = Tag::findOrFail($id);
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'slug' => 'required|string|unique:tags,slug,' . $id,
                'color' => 'nullable|string|max:7',
            ]);
            $tag->update($data);
            return response()->json(['success' => true, 'data' => $tag]);
        });
        Route::delete('/tags/{id}', function (int $id) {
            Tag::findOrFail($id)->delete();
            return response()->json(['success' => true, 'message' => 'Tag deleted.']);
        });

        // Media (admin + superadmin)
        Route::get('/media', [MediaController::class, 'index']);
        Route::post('/media/upload', [MediaController::class, 'upload']);
        Route::delete('/media/{id}', [MediaController::class, 'destroy']);

        // Docs (admin + superadmin)
        Route::get('/docs', [DocController::class, 'index']);
        Route::post('/docs', [DocController::class, 'store']);
        Route::post('/docs/reorder', [DocController::class, 'reorder']);
        Route::get('/docs/categories', [DocController::class, 'categories']);
        Route::post('/docs/categories', [DocController::class, 'storeCategory']);
        Route::post('/docs/categories/rename', [DocController::class, 'renameCategory']);
        Route::post('/docs/categories/delete', [DocController::class, 'deleteCategory']);
        Route::get('/docs/all', [DocController::class, 'all']);
        Route::get('/docs/{id}', [DocController::class, 'show']);
        Route::put('/docs/{id}', [DocController::class, 'update']);
        Route::delete('/docs/{id}', [DocController::class, 'destroy']);

        // Doc Menus (admin + superadmin)
        Route::get('/doc-menus', [DocMenuController::class, 'index']);
        Route::get('/doc-menus/flat', [DocMenuController::class, 'flat']);
        Route::post('/doc-menus', [DocMenuController::class, 'store']);
        Route::post('/doc-menus/reorder', [DocMenuController::class, 'reorder']);
        Route::put('/doc-menus/{id}', [DocMenuController::class, 'update']);
        Route::delete('/doc-menus/{id}', [DocMenuController::class, 'destroy']);

        // Search (admin + superadmin)
        Route::get('/search', [SearchController::class, 'search']);

        // Storage provider setting (readable by all admins)
        Route::get('/settings/storage', function () {
            $providers = \App\Models\Setting::getValue('allowed_storage_providers', 'both');
            return response()->json(['success' => true, 'data' => ['allowed_storage_providers' => $providers]]);
        });

        // ── Super Admin only routes ───────────────────────────────
        Route::middleware('superadmin')->group(function () {
            // Users
            Route::get('/users', [UserController::class, 'index']);
            Route::post('/users', [UserController::class, 'store']);
            Route::get('/users/{id}', [UserController::class, 'show']);
            Route::put('/users/{id}', [UserController::class, 'update']);
            Route::delete('/users/{id}', [UserController::class, 'destroy']);
            Route::put('/users/{id}/toggle-active', [UserController::class, 'toggleActive']);

            // Roles
            Route::get('/roles', [RoleController::class, 'index']);
            Route::post('/roles', [RoleController::class, 'store']);
            Route::put('/roles/{id}', [RoleController::class, 'update']);
            Route::delete('/roles/{id}', [RoleController::class, 'destroy']);

            // Settings
            Route::get('/settings', [SettingsController::class, 'index']);
            Route::put('/settings', [SettingsController::class, 'update']);

            // Activity Logs
            Route::get('/activity-logs', [ActivityLogController::class, 'index']);

            // Storage Stats
            Route::get('/storage-stats', [StorageStatsController::class, 'index']);
            Route::post('/storage-stats/refresh', [StorageStatsController::class, 'refresh']);

            // Approval Requests
            Route::get('/approval-requests', [ApprovalRequestController::class, 'index']);
            Route::post('/approval-requests/{id}/approve', [ApprovalRequestController::class, 'approve']);
            Route::post('/approval-requests/{id}/reject', [ApprovalRequestController::class, 'reject']);

            // User Permissions
            Route::get('/users/{id}/permissions', [UserPermissionController::class, 'show']);
            Route::put('/users/{id}/permissions', [UserPermissionController::class, 'update']);
        }); // end superadmin
    }); // end admin
}); // end auth:sanctum
