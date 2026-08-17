<?php

namespace App\Policies;

use App\Models\ApiUser;
use App\Models\Course;
use App\Models\Marksheet;
use Illuminate\Auth\Access\HandlesAuthorization;

class MarksheetPolicy
{
    use HandlesAuthorization;

    public function view(ApiUser $user, Marksheet $marksheet): bool
    {
        if ($user->role === ApiUser::ROLE_ADMIN) {
            return true;
        }

        if ($user->role === ApiUser::ROLE_STUDENT) {
            return $user->student_id === $marksheet->student_id;
        }

        if ($user->role === ApiUser::ROLE_PARENT) {
            return $marksheet->student->parents()->where('parent_id', $user->parent_id)->exists();
        }

        return false;
    }

    public function enterMarks(ApiUser $user, Marksheet $marksheet): bool
    {
        if ($user->role === ApiUser::ROLE_ADMIN) {
            return true;
        }

        if ($user->role === ApiUser::ROLE_FACULTY && $marksheet->exam->batch_id) {
            $facultyCourses = Course::where('faculty_id', $user->faculty_id)
                ->where('batch_id', $marksheet->exam->batch_id)
                ->exists();

            return $facultyCourses;
        }

        return false;
    }
}
