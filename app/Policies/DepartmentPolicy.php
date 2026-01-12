<?php

namespace App\Policies;

use App\Models\Access\User;
use App\Models\Department;

class DepartmentPolicy
{
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
        return $user->hasRole('Admin');
    }

    public function update(User $user, Department $department): bool
    {
        return $user->hasRole('Admin');
    }

    public function delete(User $user, Department $department): bool
    {
        return $user->hasRole('Admin');
    }
}

