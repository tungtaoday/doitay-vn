<?php

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\User\UserNotificationResource;
use App\Models\UserNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * API V1 — User notification center.
 *
 * Thin wrapper over the existing UserNotification model. Controls mutations
 * the user is allowed to make against their own notifications (mark read,
 * delete), plus fast unread-count for badge polling.
 *
 * Always scoped to `user_id = auth user` — no cross-user reads.
 */
class NotificationController extends Controller
{
    /**
     * GET /api/v1/user/notifications
     *
     * Query params:
     *   - page:        pagination (default 1)
     *   - per_page:    clamp 1..50 (default 20)
     *   - type:        filter by type (appointment, lead, campaign, system, ...)
     *   - unread_only: "1" to return only unread
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $perPage = (int) $request->integer('per_page', 20);
        $perPage = max(1, min(50, $perPage));

        $query = UserNotification::query()
            ->where('user_id', $user->id)
            ->active()
            ->latest();

        if ($type = $request->query('type')) {
            $query->byType($type);
        }
        if ($request->boolean('unread_only')) {
            $query->unread();
        }

        $page = $query->paginate($perPage);

        return response()->json([
            'data'  => UserNotificationResource::collection($page->items()),
            'meta'  => [
                'current_page' => $page->currentPage(),
                'last_page'    => $page->lastPage(),
                'per_page'     => $page->perPage(),
                'total'        => $page->total(),
            ],
            'unread_count' => $this->countUnread($user->id),
        ]);
    }

    /**
     * GET /api/v1/user/notifications/unread-count
     *
     * Lightweight endpoint for badge polling — returns ONLY the count.
     * Cheap enough to hit every 30s from the header bell.
     */
    public function unreadCount(Request $request): JsonResponse
    {
        return response()->json([
            'count' => $this->countUnread($request->user()->id),
        ]);
    }

    /**
     * POST /api/v1/user/notifications/{id}/read
     */
    public function markRead(Request $request, int $id): JsonResponse
    {
        $notification = UserNotification::query()
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        if (! $notification->is_read) {
            $notification->markAsRead();
        }

        return response()->json([
            'data'         => new UserNotificationResource($notification->fresh()),
            'unread_count' => $this->countUnread($request->user()->id),
        ]);
    }

    /**
     * POST /api/v1/user/notifications/read-all
     */
    public function markAllRead(Request $request): JsonResponse
    {
        UserNotification::query()
            ->where('user_id', $request->user()->id)
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json([
            'ok'           => true,
            'unread_count' => 0,
        ]);
    }

    /**
     * DELETE /api/v1/user/notifications/{id}
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $notification = UserNotification::query()
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        $notification->delete();

        return response()->json([
            'ok'           => true,
            'unread_count' => $this->countUnread($request->user()->id),
        ]);
    }

    private function countUnread(int $userId): int
    {
        // Không filter theo user_type: legacy logic gán user_type='company'
        // cho bất cứ user nào có sở hữu company, kể cả notification phát
        // sinh từ hành động customer-side. Inbox là per user_id.
        return UserNotification::query()
            ->where('user_id', $userId)
            ->unread()
            ->active()
            ->count();
    }
}
