<?php
namespace App\Repositories\Admin\Department;

use App\Models\Department;
use App\Models\Task\Task;
use App\Models\Task\TaskShare;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DepartmentRepository extends BaseRepository
{
    const MODEL = Department::class;

    public function getAllForDt()
    {
        return $this->query();
    }

    public function getActiveDepartments()
    {
        return $this->queryIsActive()->get();
    }

    public function getDepartmentToShareTask(Task $task)
    {
        $sharedDepartmentIds = TaskShare::where('task_id', $task->id)
            ->whereNotNull('shared_with_department_id')
            ->pluck('shared_with_department_id');

        return Department::where('is_active', true)
            ->where('id', '!=', $task->department_id)
            ->whereNotIn('id', $sharedDepartmentIds)
            ->get(['id', 'name']);
    }

    public function store(array $input) {
        return DB::transaction(function() use($input) {
            return $this->query()->create([
                'name' => $input['name'],
                'abbreviation' => $input['abbreviation'],
                'is_active' => $input['is_active'],
            ]);
        });
    }

    public function update(Model $department, array $input) {
        return DB::transaction(function() use($department, $input) {
            return $department->update([
                'name' => $input['name'],
                'abbreviation' => $input['abbreviation'],
                'is_active' => $input['is_active'],
            ]);
        });
    }

    public function changeDepartmentStatus(Model $department, array $input)
    {
        return DB::transaction(function () use($department, $input) {
            return match ($input['action']) {
                'activate'   => $this->changeStatus($department),
                'deactivate' => $this->changeStatus($department)
            };
        });
    }

    public function delete(Model $department): ?bool
    {
        $this->renamingSoftDelete($department, 'name');
        return $department->delete();
    }

    public function findDepartmentById(int $id)
    {
        return $this->find($id);
    }

    public function findDepartmentByUuid($uid)
    {
        return $this->findByUid($uid);
    }
}
