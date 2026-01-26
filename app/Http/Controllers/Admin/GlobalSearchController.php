<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Access\User;
use App\Models\Department;
use App\Models\Task\Task;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        $q = trim($request->get('q'));

        if (!$q || strlen($q) < 2) {
            return response()->json([]);
        }

        $results = collect();

        // TASKS
        Task::where('title', 'LIKE', "%$q%")
            ->orWhere('uuid', 'LIKE', "%$q%")
            ->limit(5)
            ->get()
            ->each(function ($task) use ($results) {
                $results->push([
                    'type'  => 'task',
                    'title'=> $task->title,
                    'url'  => route('admin_panel.tasks.profile', $task->uuid),
                ]);
            });

        // USERS
        User::where('name', 'LIKE', "%$q%")
            ->orWhere('email', 'LIKE', "%$q%")
            ->limit(5)
            ->get()
            ->each(function ($user) use ($results) {
                $results->push([
                    'type'  => 'user',
                    'title'=> $user->name,
                    'url'  => route('admin_panel.users.profile', $user->uuid),
                ]);
            });

        // DEPARTMENTS
        Department::where('name', 'LIKE', "%$q%")
            ->limit(5)
            ->get()
            ->each(function ($dept) use ($results) {
                $results->push([
                    'type'  => 'department',
                    'title'=> $dept->name,
                    'url'  => route('admin_panel.departments.profile', $dept->uuid),
                ]);
            });

        return response()->json($results);
    }
}
