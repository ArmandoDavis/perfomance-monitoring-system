<?php

namespace App\Repositories\Admin\Task;

use App\Models\Attachment;
use App\Models\Expense;
use App\Repositories\BaseRepository;
use App\Repositories\System\DocumentRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class TaskExpenseRepository extends BaseRepository
{
    const MODEL = Expense::class;

    /** Store task expense with optional receipt attachment */
    public function store(Model $task, array $data, ?UploadedFile $receipt = null): Expense
    {
        return DB::transaction(function () use ($task, $data, $receipt) {
            $expense = $this->query()->create([
                'task_id'    => $task->id,
                'user_id'    => user_id(),
                'amount'     => $data['amount'],
                'description'=> $data['description']
            ]);

            /** Store receipt as attachment */
            if ($receipt) {
                $attachment = app(DocumentRepository::class)->store($expense, $receipt, ['directory' => 'receipts']);
                $expense->update([
                    'receipt_path_id' => $attachment->id,
                ]);
            }
            return $expense;
        });
    }

    public function approve(Model $expense)
    {
        return DB::transaction(function () use ($expense) {
            $expense->update([
                'approved_by' => user_id(),
                'approved_at' => now(),
            ]);
        });
    }

    public function getTotalUserExpenses()
    {
        $userId = user_id();
        return $this->query()->whereHas('task.assignments', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->sum('amount');
    }

    public function getRecentExpenses()
    {
        $userId = user_id();
        return $this->query()->whereHas('task.assignments', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->latest()->take(5)->get()
            ->map(function ($expense) {
                return [
                    'type' => 'expense',
                    'title' => 'Expense Added',
                    'description' => 'Amount: ' . number_format($expense->amount, 2) . ' TZS',
                    'date' => $expense->created_at,
                ];
            });
    }
}
