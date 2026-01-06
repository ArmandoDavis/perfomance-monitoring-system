@extends('admin.layouts.admin')

@section('content')
<h1>Edit Sub-task for: {{ $task->title }}</h1>

<form action="{{ route('admin.tasks.subtasks.update', [$task->id, $subTask->id]) }}" method="POST">
    @csrf
    @method('PUT')

    <label>Title</label>
    <input type="text" name="title" value="{{ $subTask->title }}" required><br><br>

    <label>Budget</label>
    <input type="number" name="allocated_amount" value="{{ $subTask->allocated_amount }}" step="0.01" required><br><br>

    <button type="submit">Update Sub-task</button>
</form>
@endsection
