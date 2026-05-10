<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    private const UNREAD_CACHE_TTL_SECONDS = 60;

    private function unreadCountCacheKey(int|string $userId): string
    {
        return 'notifications.unread_count.' . $userId;
    }

    private function forgetUnreadCountCache(Request $request): void
    {
        Cache::forget($this->unreadCountCacheKey($request->user()->getAuthIdentifier()));
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $notifications = $user->notifications()->latest()->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $notifications->items(),
            'unread_count' => $this->countUnreadForUser($user),
            'meta' => [
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'total' => $notifications->total(),
            ],
        ]);
    }

    public function unreadCount(Request $request): JsonResponse
    {
        $user = $request->user();
        $key = $this->unreadCountCacheKey($user->getAuthIdentifier());

        $count = Cache::remember(
            $key,
            self::UNREAD_CACHE_TTL_SECONDS,
            fn () => $this->countUnreadForUser($user)
        );

        return response()->json([
            'success' => true,
            'unread_count' => (int) $count,
        ]);
    }

    /**
     * Single indexed COUNT query (no Eloquent collection).
     */
    private function countUnreadForUser(User $user): int
    {
        return (int) DB::table('notifications')
            ->where('notifiable_type', $user->getMorphClass())
            ->where('notifiable_id', $user->getKey())
            ->whereNull('read_at')
            ->count();
    }

    public function markAsRead(Request $request, string $id): JsonResponse
    {
        $notification = $request->user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $notification->markAsRead();
        $this->forgetUnreadCountCache($request);

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read.',
        ]);
    }

    public function markAllRead(Request $request): JsonResponse
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);
        $this->forgetUnreadCountCache($request);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read.',
        ]);
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $notification = $request->user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $wasUnread = $notification->read_at === null;

        $notification->delete();

        if ($wasUnread) {
            $this->forgetUnreadCountCache($request);
        }

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted.',
        ]);
    }
}
