<?php

namespace App\Policies\System;

use App\Models\Access\User;
use App\Models\Department;
use Illuminate\Auth\Access\HandlesAuthorization;

class DepartmentPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->hasRole('Admin')) {
            return true;
        }
    }


    public function viewAny(User $user): bool
    {
        return $user->can('department.manage');
    }

    public function view(User $user, Department $department): bool
    {
        return $user->can('department.manage');
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Department $department): bool
    {
        return false;
    }

    public function delete(User $user, Department $department): bool
    {
        return false;
    }
}
