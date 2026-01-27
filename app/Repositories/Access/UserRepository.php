<?php

namespace App\Repositories\Access;

use App\Models\Access\Permission;
use App\Models\Access\Role;
use App\Models\Access\User;
use App\Models\System\CodeValue;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserRepository extends BaseRepository
{
    const MODEL = User::class;

    public function store(array $input)
    {
        return DB::transaction(function() use($input) {
            $userType = CodeValue::getCodeValueByReference('USER002');

            $emailVerifiedAt = null;
            if (isset($input['user_type_id'])) {
                $emailVerifiedAt = now();
            }

            $user = $this->createMassAssign('users', [
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => $input['password'] ?? 12345678,
                'phone' => $input['phone'],
                'is_active' => filled($input['is_active']),
                'is_super_admin' => $input['is_super_admin'] ?? false,
                'user_type_id' => $input['user_type_id'] ?? $userType->id,
                'email_verified_at' =>  $emailVerifiedAt,
                'uuid' => str_unique()
            ]);

            if (isset($input['roles']) && empty($input['password'])) {
                $this->assignRolesAndPermissions($user, $input['roles']);
                //$this->sendEmailWithPassword($user, $rawPassword);
            } else {
                //$this->sendConfirmationCode($user);
            }
            return $user;
        });
    }

    public function update($user, array $input)
    {
        $adminType = CodeValue::getCodeValueByReference('USER001');

        return DB::transaction(function() use($user, $input, $adminType) {
            $userTypeId = $input['user_type_id'] ?? $user->user_type_id;

            $this->updateMassAssign('users', $user->id, [
                'name' => $input['name'],
                'email' => $input['email'],
                'phone' => $input['phone'],
                'is_active' => $input['is_active'],
                'user_type_id'=> $userTypeId,
            ]);
            if ($adminType && $adminType->id == $userTypeId) {
                $adminRole = Role::firstOrCreate(['name' => 'Admin']);
                $adminRole->syncPermissions(Permission::all());

                $user->roles()->syncWithoutDetaching([$adminRole->id]);
            }
            if (!empty($input['roles'])) {
                $this->assignRolesAndPermissions($user, $input['roles']);
            }
            return $user->fresh();
        });
    }

    protected function assignRolesAndPermissions(User $user, array $roleIds): bool
    {
        $user->roles()->sync($roleIds);
        $permissionIds = Role::whereIn('id', $roleIds)->with('permissions')->get()
            ->pluck('permissions.*.id')
            ->flatten()
            ->unique()->filter()->toArray();
        $user->permissions()->sync($permissionIds);
        return true;
    }

    public function delete($user)
    {
        return DB::transaction(function () use ($user) {
            $this->renamingSoftDelete($user, 'email');
            $this->renamingSoftDelete($user, 'phone');
            return $user->delete();
        });
    }

    public function updatePassowrd(Model $user, $input){
        $user->update(['password' => $input['password']]);
    }

    public function toggleStatus(Model $user, array $input)
    {
        return DB::transaction(function () use($user, $input) {
            return match ($input['action']) {
                'activate' => $this->changeStatus($user),
                'deactivate' => $this->changeStatus($user),
                default => throw new \Exception(__('Invalid action')),
            };
        });
    }

    public function getActiveStaffs()
    {
        return $this->queryIsActive()->get();
    }

    public function getNonEvaluatedUserForThisTask($taskId)
    {
        return $this->queryIsActive()
            ->whereIn('id', function ($q) use ($taskId) {
                $q->select('user_id')
                    ->from('task_assignments')
                    ->where('task_id', $taskId);
            })
            ->whereNotIn('id', function ($q) use ($taskId) {
                $q->select('user_id')
                    ->from('performance_scores')
                    ->where('task_id', $taskId);
            })
            ->get();
    }

    public function getAllForDt()
    {
        $staffType = CodeValue::getCodeValueByReference('USER002');
        $adminType = CodeValue::getCodeValueByReference('USER001');
//        return $this->query()->where('user_type_id', $staffType->id)->orWhere('user_type_id', $adminType->id)->get();
        return $this->query()->select(['users.*', 'code_values.name as user_type'])->leftJoin('code_values', 'code_values.id', '=', 'users.user_type_id');
    }

    public function findByUid($uuid)
    {
        return $this->getByUid($uuid);
    }

    public function getStaffUsers()
    {
        $userType = CodeValue::getCodeValueByReference('USER001');
        return $this->query()->where('user_type_id', $userType->id)->orderBy('created_at', 'desc')->get();
    }

    public function getAdminUsers()
    {
        $userType = CodeValue::getCodeValueByReference('USER001');
        return $this->query()->where('user_type_id', $userType->id)->orderBy('created_at', 'desc')->get();
    }
}
