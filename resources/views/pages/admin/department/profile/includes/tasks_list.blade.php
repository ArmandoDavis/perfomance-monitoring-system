<div class="table-responsive">
    <table class="table table-hover align-middle border-top">
        <thead class="table-light">
        <tr>
            <th>Task Title</th>
            <th>Budget (Allocated)</th>
            <th>Timeline</th>
            <th>Status</th>
            <th class="text-center">Active</th>
            <th class="text-end">Action</th>
        </tr>
        </thead>
        <tbody>
        @forelse($department->tasks as $task)
            <tr>
                <td>
                    <div class="fw-bold text-dark">{{ $task->title }}</div>
                    <small class="text-muted">{{ Str::limit($task->description, 50) }}</small>
                </td>
                <td>
                    <span class="fw-medium">{{ number_2_format($task->allocated_budget) }}</span>
                </td>
                <td>
                    <div class="small">
                        <i class="fas fa-calendar-day me-1 text-primary"></i>
                        {{ short_date_format($task->start_date) }}
                        <i class="fas fa-arrow-right mx-1 small text-muted"></i>
                        <i class="fas fa-calendar-check me-1 text-danger"></i>
                        {{ short_date_format($task->end_date) }}
                    </div>
                </td>
                <td>
                    {{-- Assuming task has a relationship called 'status' to CodeValue model --}}
                    <span class="badge bg-soft-primary text-primary border border-primary px-2">
                        {{ $task->status->name ?? 'Unknown' }}
                    </span>
                </td>
                <td class="text-center">
                    {!! getBooleanBadge($task->is_active) !!}
                </td>
                <td class="text-end">
                    <a href="{{ route('admin_panel.tasks.profile', $task->uuid) }}" class="btn btn-sm btn-outline-dark">
                        <i class="fas fa-eye"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center py-5 text-muted">
                    No tasks found for the **{{ $department->name }}** department.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
