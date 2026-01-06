@extends('admin.layouts.admin')

@section('content')
<h2>Subtasks for: {{ $task->title }}</h2>

<a href="{{ route('admin.subtasks.create', $task->id) }}">
    + Add Subtask
</a>

<table border="1" cellpadding="5">
    <thead>
        <tr>
            <th>Title</th>
            <th>Budget</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        @foreach($subtasks as $subtask)
        <tr>
            <td>{{ $subtask->title }}</td>
            <td>{{ number_format($subtask->budget, 2) }}</td>
            <td>{{ $subtask->status }}</td>
            <td>
                <a href="{{ route('admin.subtasks.edit', $subtask->id) }}">Edit</a>

                <form method="POST" action="{{ route('admin.subtasks.destroy', $subtask->id) }}" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button onclick="return confirm('Delete this subtask?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<p><strong>Total Task Budget:</strong>
   {{ number_format($task->total_budget, 2) }}
</p>
@endsection
