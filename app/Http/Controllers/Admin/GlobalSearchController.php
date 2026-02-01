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
        $user = user();
        $results = collect();

        $panel = 'frontend';
        if ($user->hasRole('Admin')) {
            $panel = 'admin_panel';
        } elseif ($user->hasRole('Head of Department')) {
            $panel = 'hod_panel';
        }

        // TASKS
        Task::query()
            ->where(function($query) use ($q) {
                $query->where('title', 'LIKE', "%$q%")
                    ->orWhere('uuid', 'LIKE', "%$q%");
            })
            ->when(!$user->hasRole('Admin'), function($query) use ($user) {
                return $query->where('department_id', $user->department_id)
                    ->orWhereHas('shares', function($s) use ($user) {
                        $s->where('shared_with_user_id', $user->id);
                    });
            })
            ->limit(5)->get()
            ->each(function ($task) use ($results, $panel) {
                $results->push([
                    'type'  => __('Task'),
                    'title' => $task->title,
                    'url'   => route("{$panel}.tasks.profile", $task->uuid),
                    'icon'  => 'assignment'
                ]);
            });

        // USERS SEARCH (Typically for Admin/HOD)
        if ($user->can('user.view')) {
            User::where('name', 'LIKE', "%$q%")
                ->orWhere('email', 'LIKE', "%$q%")
                ->limit(5)->get()
                ->each(function ($u) use ($results, $panel) {
                    $results->push([
                        'type'  => __('Staff'),
                        'title' => $u->name,
                        'url'   => route("{$panel}.users.profile", $u->uuid),
                        'icon'  => 'person'
                    ]);
                });
        }

        // DEPARTMENTS SEARCH (Admin Only)
        if ($user->hasRole('Admin')) {
            Department::where('name', 'LIKE', "%$q%")
                ->limit(5)->get()
                ->each(function ($dept) use ($results, $panel) {
                    $results->push([
                        'type'  => __('Department'),
                        'title' => $dept->name,
                        'url'   => route("{$panel}.departments.profile", $dept->uuid),
                        'icon'  => 'corporate_fare'
                    ]);
                });
        }

        return response()->json($results);
    }
}
