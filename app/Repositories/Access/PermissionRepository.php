<?php
namespace App\Repositories\Access;


use App\Models\Access\Permission;
use App\Models\Access\Role;
use App\Models\Access\User;
use App\Repositories\BaseRepository;

class PermissionRepository extends BaseRepository
{

    const  MODEL = Permission::class;

    /*Get all permissions*/
    public  function  getAll() {
        return $this->query()->orderBy('created_at')->get();
    }

    public function getAllGrouped()
    {
        return $this->getAll()->groupBy(function($p) {
            return strpos($p->name, '.') !== false ? explode('.', $p->name)[0] : 'general';
        });
    }

    public function getPermissionsByRole(Role $role)
    {
        return $role->permissions()->select('id', 'name')->orderBy('name')->get();
    }

    /*Check if permission is in user roles*/
    public function checkIfPermissionIsInUserRoles($user_id, $permission_id)
    {
        $user = User::query()->find($user_id);
        $check = $user->roles()->whereHas('permissions', function($query) use($permission_id){
            $query->where('permissions.id', $permission_id);
        })->count();
        if($check > 0)
        {
            return true;
        }else{
            return false;
        }
    }
}
