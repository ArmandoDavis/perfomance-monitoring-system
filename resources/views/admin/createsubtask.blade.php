@extends('admin.layouts.admin')

@section('content')
<h1>Create Sub-task for: {{ $task->title }}</h1>

<form action="{{ route('admin.tasks.subtasks.store', $task->id) }}" method="POST">
    @csrf

    <label>Title</label>
    <input type="text" name="title" placeholder="Sub-task Title" required><br><br>

    <label>Budget</label>
    <input type="number" name="allocated_amount" step="0.01" placeholder="Budget Amount" required><br><br>

    <button type="submit">Save Sub-task</button>
</form>
@endsection
