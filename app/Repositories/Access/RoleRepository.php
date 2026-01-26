<?php
namespace App\Repositories\Access;

use App\Models\Access\Role;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RoleRepository extends BaseRepository
{
    const MODEL = Role::class;

    public function  getDetail($id){

        return $this->query()->where('id', $id)->first();
    }

    public function forSelect()
    {
        return $this->query()->select('name', 'id')->get();
    }


    public function getAllForDt()
    {
        return $this->query()->withCount('users')->get();
    }

    public function store(array $input)
    {
        return DB::transaction(function () use ($input) {
            return $this->query()->create([
                'name' => $input['name'],
            ]);
        });
    }


    /*Update Role and Permissions */
    public function update(array $input, Model $role)
    {
        return  DB :: transaction(function() use ($input, $role){
            $this->updateRolePermissions($input, $role);
            return $role;
        });
    }

    /*Update sync permissions with role*/
    protected function updateRolePermissions(array $input, Model $role)
    {
        return DB::transaction(function () use ($input, $role) {
            if (isset($input['permissions']) && is_array($input['permissions'])) {
                $role->permissions()->sync($input['permissions']);
            }
            else {
                $role->permissions()->detach();
            }
            return $role;
        });
    }

    public function delete(Model $role)
    {
        $role->permissions()->sync([]);
        $this->renamingSoftDelete($role, 'name');
        $role->delete();
        return true;
    }
}
