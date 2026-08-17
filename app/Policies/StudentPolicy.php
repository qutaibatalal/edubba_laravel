<?php

namespace App\Policies;

use App\Models\ApiUser;
use App\Models\Student;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudentPolicy
{
    use HandlesAuthorization;

    public function view(ApiUser $user, Student $student): bool
    {
        if ($user->role === ApiUser::ROLE_ADMIN) {
            return true;
        }

        if ($user->role === ApiUser::ROLE_STUDENT && $user->student_id === $student->id) {
            return true;
        }

        if ($user->role === ApiUser::ROLE_PARENT) {
            return $student->parents()->where('parent_id', $user->parent_id)->exists();
        }

        if ($user->role === ApiUser::ROLE_FACULTY) {
            return $user->faculty_id !== null;
        }

        return false;
    }
}
