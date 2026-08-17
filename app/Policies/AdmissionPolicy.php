<?php

namespace App\Policies;

use App\Models\Admission;
use App\Models\ApiUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdmissionPolicy
{
    use HandlesAuthorization;

    public function view(ApiUser $user, Admission $admission): bool
    {
        return true;
    }

    public function submit(ApiUser $user, Admission $admission): bool
    {
        return in_array($user->role, [ApiUser::ROLE_ADMIN, ApiUser::ROLE_STUDENT, ApiUser::ROLE_PARENT]);
    }

    public function approve(ApiUser $user, Admission $admission): bool
    {
        return $user->role === ApiUser::ROLE_ADMIN;
    }

    public function reject(ApiUser $user, Admission $admission): bool
    {
        return $user->role === ApiUser::ROLE_ADMIN;
    }

    public function admit(ApiUser $user, Admission $admission): bool
    {
        return $user->role === ApiUser::ROLE_ADMIN;
    }
}
