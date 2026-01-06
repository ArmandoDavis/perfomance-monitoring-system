@extends('admin.layouts.admin')

@section('content')
<h1>Edit Budget for Task: {{ $task->title }}</h1>

<form method="POST" action="{{ route('admin.tasks.budget.update', [$task->id, $budget->id]) }}">
    @csrf
    @method('PUT')

    <label>Allocated Amount</label><br>
    <input type="number" step="0.01" name="allocated_amount" value="{{ $budget->allocated_amount }}" required><br><br>

    <button type="submit">Update Budget</button>
</form>

<form method="POST" action="{{ route('admin.tasks.budget.destroy', [$task->id, $budget->id]) }}" style="margin-top:10px;">
    @csrf
    @method('DELETE')
    <button type="submit" style="background-color:red;color:white;">Delete Budget</button>
</form>
@endsection
