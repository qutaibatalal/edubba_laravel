<?php

namespace App\Http\Middleware;

use App\Models\ApiUser;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Ensures the authenticated user can only access their own data.
 * Students see only their own records, parents see only their children,
 * faculty see only their assigned batches.
 */
class EnsureStudentOwnership
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401, 'Unauthenticated');
        }

        $request->merge(['_auth_user' => $user]);

        match ($user->role) {
            ApiUser::ROLE_STUDENT => $this->enforceStudent($request, $user),
            ApiUser::ROLE_PARENT => $this->enforceParent($request, $user),
            ApiUser::ROLE_FACULTY => $this->enforceFaculty($request, $user),
            default => null,
        };

        return $next($request);
    }

    protected function enforceStudent(Request $request, ApiUser $user): void
    {
        $student = $user->student;

        if (! $student) {
            abort(404, 'Student profile not found');
        }

        $request->merge(['_student' => $student]);

        // Block any attempt to read another student's data via query params
        $request->offsetUnset('student_id');
        $request->offsetUnset('id');
    }

    protected function enforceParent(Request $request, ApiUser $user): void
    {
        $parent = $user->parent;

        if (! $parent) {
            abort(404, 'Parent profile not found');
        }

        $request->merge(['_parent' => $parent]);
    }

    protected function enforceFaculty(Request $request, ApiUser $user): void
    {
        $faculty = $user->faculty;

        if (! $faculty) {
            abort(404, 'Faculty profile not found');
        }

        $request->merge(['_faculty' => $faculty]);
    }
}
