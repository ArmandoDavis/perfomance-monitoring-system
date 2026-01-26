<?php

namespace App\Repositories\Access;

use App\Models\Access\Role;
use App\Models\Access\User;
use App\Models\System\CodeValue;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class UserRepository extends BaseRepository
{
    const MODEL = User::class;

    public function store(array $input)
    {
        return DB::transaction(function() use($input) {
            //$rawPassword = $this->generatePassword();
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
        return DB::transaction(function() use($user, $input) {
            $this->updateMassAssign('users', $user->id, [
                'name' => $input['name'],
                'email' => $input['email'],
                'phone' => $input['phone'],
                'is_active' => $input['is_active'],
            ]);
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

    public function updatePassword($input){
        $user = User::getUserIdByEmail($input['email']);
        $this->passwordUpdateUtil($user, $input['password']);
        return $user;
    }

    public function resendPassword($input){
        $user = User::getUserIdByEmail($input['email']);
        $newPassword = $this->generatePassword();
        $this->passwordUpdateUtil($user, $newPassword);
        return $user;
    }

    protected function passwordUpdateUtil($user, $password)
    {
        $user->update(['password' => $password]);
        $this->sendEmailWithPassword($user, $password);
    }

    public function getActiveStaffs()
    {
        return $this->queryIsActive()->get();
    }

    public function getAllForDt()
    {
        $staffType = CodeValue::getCodeValueByReference('USER002');
        $adminType = CodeValue::getCodeValueByReference('USER001');
//        return $this->query()->where('user_type_id', $staffType->id)->orWhere('user_type_id', $adminType->id)->get();
        return $this->query()
            ->select(['users.*', 'code_values.name as user_type'])
            ->leftJoin('code_values', 'code_values.id', '=', 'users.user_type_id');
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
