<?php
namespace App\Notifications\Admin\Task;

use App\Models\Task\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaskAccessNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Task $task,
        protected string $actionType, // 'shared', 'revoked', 'modified'
        protected string $sharedBy
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $messages = [
            'shared'   => __("A task ':title' has been shared with you.", ['title' => $this->task->title]),
            'revoked'  => __("Your access to task ':title' has been revoked.", ['title' => $this->task->title]),
            'modified' => __("Your access level for task ':title' has been updated.", ['title' => $this->task->title]),
        ];

        $routePrefix = 'frontend.tasks.profile';
        if ($notifiable->hasRole('Head of Department')) {
            $routePrefix = 'hod_panel.tasks.profile';
        }
        if ($notifiable->hasRole('Admin')) {
            $routePrefix = 'admin_panel.tasks.profile';
        }
        return [
            'task_id'    => $this->task->id,
            'task_uuid'  => $this->task->uuid,
            'title'      => $this->task->title,
            'message'    => $messages[$this->actionType] ?? __('Task access updated'),
            'shared_by'  => $this->sharedBy,
            'action_url' => route($routePrefix, $this->task->uuid)
        ];
    }
}
