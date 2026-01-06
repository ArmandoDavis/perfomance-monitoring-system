@extends('admin.layouts.admin')

@section('content')
<div class="container">
    <h1>Create Task</h1>

    <form method="POST" action="{{ route('admin.tasks.store') }}">
        @csrf

        {{-- Task Title --}}
        <div class="mb-3">
            <label>Task Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        {{-- Task Description --}}
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="4" required></textarea>
        </div>

        {{-- Department --}}
        <div class="mb-3">
            <label>Department</label>
            <select name="department_id" class="form-control" required>
                <option value="">-- Select Department --</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}">
                        {{ $department->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Start Date --}}
        <div class="mb-3">
            <label>Start Date</label>
            <input type="date" name="start_date" class="form-control" required>
        </div>

        {{-- Due Date --}}
        <div class="mb-3">
            <label>Due Date</label>
            <input type="date" name="due_date" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">
            Save Task
        </button>
    </form>
</div>
@endsection
