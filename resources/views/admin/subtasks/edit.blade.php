@extends('admin.layouts.admin')

@section('content')
<h2>Edit Subtask</h2>

<form method="POST" action="{{ route('admin.subtasks.update', $subtask->id) }}">
    @csrf
    @method('PUT')

    <div>
        <label>Title</label>
        <input type="text" name="title" value="{{ $subtask->title }}" required>
    </div>

    <div>
        <label>Description</label>
        <textarea name="description">{{ $subtask->description }}</textarea>
    </div>

    <div>
        <label>Budget</label>
        <input type="number" name="budget" step="0.01" value="{{ $subtask->budget }}" required>
    </div>

    <div>
        <label>Status</label>
        <select name="status">
            <option value="not_started" {{ $subtask->status == 'not_started' ? 'selected' : '' }}>Not Started</option>
            <option value="in_progress" {{ $subtask->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
            <option value="completed" {{ $subtask->status == 'completed' ? 'selected' : '' }}>Completed</option>
        </select>
    </div>

    <button type="submit">Update Subtask</button>
</form>
@endsection
