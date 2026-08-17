<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiUser;
use App\Models\Device;
use App\Models\NotificationLog;
use App\Models\PushToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * GET /notifications
     */
    public function index(Request $request): JsonResponse
    {
        $query = NotificationLog::orderByDesc('id');

        $user = $request->user();
        if ($user->role === ApiUser::ROLE_STUDENT) {
            $query->where('student_id', $user->student_id);
        } elseif ($user->role === ApiUser::ROLE_PARENT) {
            $query->whereHas('student', fn ($q) => $q->whereHas('parents', fn ($p) => $p->where('parent_id', $user->parent_id)));
        }

        $notifications = $query->limit(100)->get();

        return response()->json([
            'status' => 'success',
            'data' => $notifications->map(fn ($n) => [
                'id' => $n->id,
                'channel' => $n->channel,
                'recipient' => $n->recipient,
                'body' => $n->body,
                'state' => $n->state,
                'is_read' => $n->read_at !== null,
                'created_at' => $n->created_at?->toDateTimeString(),
            ]),
        ]);
    }

    /**
     * POST /notifications/register-device
     * Body: { fcm_token, device_type, device_id }
     */
    public function registerDevice(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'fcm_token' => 'required|string',
            'device_type' => 'nullable|in:android,ios,web',
            'device_id' => 'nullable|string|max:255',
        ]);

        $user = $request->user();

        $deviceId = $validated['device_id'] ?? null;
        $device = null;

        if ($deviceId) {
            $device = Device::firstOrCreate(
                ['device_token' => $deviceId],
                [
                    'platform' => $validated['device_type'] ?? null,
                    'active' => true,
                ]
            );
        }

        $token = PushToken::updateOrCreate(
            ['api_user_id' => $user->id, 'token' => $validated['fcm_token']],
            [
                'device_id' => $device?->id,
                'provider' => 'fcm',
                'device_type' => $validated['device_type'] ?? null,
                'active' => true,
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Device registered',
            'data' => ['id' => $token->id],
        ]);
    }

    /**
     * POST /notifications/{id}/read
     */
    public function markRead(Request $request, int $id): JsonResponse
    {
        $notification = NotificationLog::find($id);

        if (! $notification || ! $this->canAccess($request->user(), $notification)) {
            abort(404, 'Notification not found');
        }

        if ($notification->read_at === null) {
            $notification->read_at = now();
            $notification->save();
        }

        return response()->json(['status' => 'success', 'message' => 'Marked as read']);
    }

    /**
     * POST /notifications/read-all
     */
    public function readAll(Request $request): JsonResponse
    {
        $query = NotificationLog::whereNull('read_at');

        $user = $request->user();
        if ($user->role === ApiUser::ROLE_STUDENT) {
            $query->where('student_id', $user->student_id);
        } elseif ($user->role === ApiUser::ROLE_PARENT) {
            $query->whereHas('student', fn ($q) => $q->whereHas('parents', fn ($p) => $p->where('parent_id', $user->parent_id)));
        }

        $count = $query->update(['read_at' => now()]);

        return response()->json([
            'status' => 'success',
            'message' => "{$count} notification(s) marked as read",
            'data' => ['updated' => $count],
        ]);
    }

    protected function canAccess(ApiUser $user, NotificationLog $notification): bool
    {
        if ($user->role === ApiUser::ROLE_STUDENT) {
            return $notification->student_id === $user->student_id;
        }

        if ($user->role === ApiUser::ROLE_PARENT) {
            return $notification->student_id
                && $notification->student
                && $notification->student->parents()->where('parent_id', $user->parent_id)->exists();
        }

        return true;
    }
}
