<div class="card-body p-0">
    <table class="table table-bordered mb-0">
        <thead class="table-light">
            <tr>
                <th>{{ __('User') }}</th>
                <th>{{ __('Assigned Budget') }}</th>
                <th>{{ __('Spent Amount') }}</th>
                <th>{{ __('Remaining Budget') }}</th>
                <th>{{ __('Active?') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($task->assignments as $assignment)
                <tr>
                    <td>{{ optional($assignment->user)->name }}</td>
                    <td>{{ number_2_format($assignment->assigned_budget) }}</td>
                    <td>{{ number_2_format($assignment->spent_amount) }}</td>
                    <td>{{ number_2_format($assignment->remaining_budget) }}</td>
                    <td> {!! getStatusBadge($assignment->is_active) !!}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">{{ __('No assignments yet') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
