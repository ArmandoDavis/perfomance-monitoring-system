@extends('admin.layouts.admin')

@section('content')
<div class="container">
    <h2>Allocate Budget</h2>

    <p><strong>Task:</strong> {{ $task->title }}</p>

    <form method="POST" action="{{ route('admin.tasks.budget.store', $task->id) }}">
        @csrf

        <div class="mb-3">
            <label>Allocated Amount</label>
            <input type="number" step="0.01" name="allocated_amount" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Description (optional)</label>
            <textarea name="description" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">
            Save Budget
        </button>
    </form>
</div>
@endsection
