<?php
namespace App\Repositories\Admin\Task;

use App\Models\Comment;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CommentRepository extends BaseRepository
{
    const MODEL = Comment::class;

    public function getAllForDt()
    {
        return $this->query()->get();
    }

    public function getActiveTasks()
    {
        return $this->queryIsActive();
    }

    public function store(Model $task, array $input) {
        return DB::transaction(function() use($task, $input) {
            return $this->query()->create([
                'content' => $input['content'],
                'task_id' => $task->id,
                'user_id' => user_id(),
            ]);
        });
    }

    public function update(Model $task, array $input) {
        return DB::transaction(function() use($task, $input) {
            return $this->query()->create([
                'content' => $input['content'],
                'task_id' => $input['task_id'],
            ]);
        });
    }

    public function delete(Model $comment): ?bool
    {
        return DB::transaction(function () use($comment) {
            return $comment->delete();
        });
    }
}
