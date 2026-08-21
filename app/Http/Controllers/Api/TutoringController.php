<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LeadResource;
use App\Http\Resources\StudyGroupResource;
use App\Http\Resources\StudyGroupSessionResource;
use App\Http\Resources\SubscriptionResource;
use App\Http\Resources\WalletResource;
use App\Models\ApiUser;
use App\Models\Lead;
use App\Models\StudyGroup;
use App\Models\StudyGroupAttendance;
use App\Models\StudyGroupSession;
use App\Models\Subscription;
use App\Models\TutoringPackage;
use App\Models\Wallet;
use App\Services\WalletService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TutoringController extends Controller
{
    /**
     * GET /tutoring/subscriptions
     */
    public function subscriptions(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = Subscription::with('studyGroup', 'tutor');

        if ($user->role === ApiUser::ROLE_STUDENT) {
            $query->where('student_id', $user->student_id);
        } elseif ($user->role === ApiUser::ROLE_PARENT) {
            $query->where('parent_id', $user->parent_id)
                ->orWhereHas('student', fn ($q) => $q->whereHas('parents', fn ($p) => $p->where('parent_id', $user->parent_id)));
        } elseif ($user->role === ApiUser::ROLE_FACULTY && $user->faculty) {
            $query->whereHas('tutor', fn ($q) => $q->where('faculty_id', $user->faculty_id));
        }

        return response()->json([
            'status' => 'success',
            'data' => SubscriptionResource::collection($query->orderByDesc('id')->get()),
        ]);
    }

    /**
     * GET /tutoring/groups
     */
    public function groups(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = StudyGroup::with('subject')->withCount('students');

        if ($user->role === ApiUser::ROLE_STUDENT) {
            $query->whereHas('students', fn ($q) => $q->where('student_id', $user->student_id));
        } elseif ($user->role === ApiUser::ROLE_FACULTY && $user->faculty) {
            $query->whereHas('tutor', fn ($q) => $q->where('faculty_id', $user->faculty_id));
        }

        return response()->json([
            'status' => 'success',
            'data' => StudyGroupResource::collection($query->get()),
        ]);
    }

    /**
     * GET /tutoring/group/{id}/sessions
     */
    public function groupSessions(Request $request, int $groupId): JsonResponse
    {
        $sessions = StudyGroupSession::with('studyGroup', 'attendances.student')
            ->where('study_group_id', $groupId)
            ->orderBy('date')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => StudyGroupSessionResource::collection($sessions),
        ]);
    }

    /**
     * POST /tutoring/session/{id}/attendance
     * Body: { "statuses": { student_id: "present|absent|late", ... } }
     */
    public function markSession(Request $request, int $sessionId): JsonResponse
    {
        $session = StudyGroupSession::findOrFail($sessionId);

        $faculty = $request->user()->faculty;
        if ($faculty && $session->tutor?->faculty_id !== $faculty->id) {
            if ($request->user()->role !== ApiUser::ROLE_ADMIN) {
                abort(403, 'Not allowed');
            }
        }

        $request->validate([
            'statuses' => 'required|array',
            'statuses.*' => 'required|in:present,absent,late',
        ]);

        foreach ($request->input('statuses') as $studentId => $status) {
            StudyGroupAttendance::updateOrCreate(
                ['study_group_session_id' => $session->id, 'student_id' => $studentId],
                ['status' => $status]
            );
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Session attendance saved',
            'data' => StudyGroupSessionResource::make($session->fresh('attendances.student')),
        ]);
    }

    /**
     * GET /tutoring/wallet
     */
    public function wallet(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role === ApiUser::ROLE_STUDENT && $user->student_id) {
            $wallet = WalletService::forStudent($user->student_id);
        } elseif ($user->role === ApiUser::ROLE_PARENT && $user->parent_id) {
            $wallet = Wallet::where('parent_id', $user->parent_id)->first();
        } else {
            return response()->json([
                'status' => 'success',
                'data' => null,
            ]);
        }

        if (! $wallet) {
            return response()->json([
                'status' => 'success',
                'data' => null,
            ]);
        }

        $wallet->load('transactions');

        return response()->json([
            'status' => 'success',
            'data' => new WalletResource($wallet),
        ]);
    }

    /**
     * GET /tutoring/packages
     */
    public function packages(): JsonResponse
    {
        $packages = TutoringPackage::where('active', true)
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $packages,
        ]);
    }

    /**
     * GET /tutoring/leads (agent view)
     */
    public function leads(Request $request): JsonResponse
    {
        $leads = Lead::with('source', 'stage')
            ->orderByDesc('id')
            ->paginate(50);

        return response()->json([
            'status' => 'success',
            'data' => LeadResource::collection($leads),
        ]);
    }

    /**
     * GET /faculty/study-groups (tutor view of own groups)
     */
    public function myGroups(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role === ApiUser::ROLE_ADMIN) {
            $query = StudyGroup::with('subject')->withCount('students');
        } elseif ($user->faculty) {
            $query = StudyGroup::with('subject')
                ->withCount('students')
                ->whereHas('tutor', fn ($q) => $q->where('faculty_id', $user->faculty_id));
        } else {
            abort(403, 'Not allowed');
        }

        return response()->json([
            'status' => 'success',
            'data' => StudyGroupResource::collection($query->get()),
        ]);
    }
}
