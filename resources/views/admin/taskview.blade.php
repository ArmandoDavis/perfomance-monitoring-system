@extends('admin.layouts.admin')
@section('content')
<div class="container">
    <h2>Tasks</h2>

    <a href="{{ route('admin.tasks_create') }}" class="btn btn-success mb-3">
        Create Task
    </a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Total Budget</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $task)
                <tr>
                    <td>{{ $task->id }}</td>
                    <td>{{ $task->title }}</td>
                    <td>{{ number_format($task->total_budget, 2) }}</td>
                   
                    <td>{{ $task->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.tasks.subtasks.profile', $task->id) }}">View Sub-tasks</a>
                    </td>
                    
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
