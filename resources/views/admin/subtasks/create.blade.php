@extends('admin.layouts.admin')

@section('content')
<h2>Create Subtask for: {{ $task->title }}</h2>

<form method="POST" action="{{ route('admin.subtasks.store', $task->id) }}">
    @csrf

    <div>
        <label>Subtask Title</label>
        <input type="text" name="title" required>
    </div>

    <div>
        <label>Description</label>
        <textarea name="description"></textarea>
    </div>

    <div>
        <label>Budget</label>
        <input type="number" name="budget" step="0.01" required>
    </div>

    <button type="submit">Save Subtask</button>
</form>
@endsection
