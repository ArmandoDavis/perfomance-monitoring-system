@extends('admin.layouts.admin')
@section('content')
<h1>Sub-tasks for: {{ $task->title }}</h1>

<a href="{{ route('admin.tasks.subtasks.create', $task->id) }}">Add New Sub-task</a>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Title</th>
            <th>Description</th>
            <th>Budget</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($subTasks as $sub)
        <tr>
            <td>{{ $sub->id }}</td>
            <td>{{ $sub->title }}</td>
            <td>{{ $sub->description }}</td>
            <td>{{ number_format($sub->allocated_amount, 2) }}</td>
            <td>
                <a href="{{ route('admin.tasks.subtasks.edit', [$task->id, $sub->id]) }}">Edit</a>
                <form method="POST" action="{{ route('admin.tasks.subtasks.destroy', [$task->id, $sub->id]) }}" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3"><strong>Total Budget</strong></td>
            <td colspan="2"><strong>{{ number_format($subTasks->sum('allocated_amount'), 2) }}</strong></td>
        </tr>
    </tfoot>
</table>
@endsection
