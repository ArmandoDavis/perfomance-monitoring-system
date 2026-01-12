<div class="card-body p-0">
    <table class="table table-bordered mb-0">
        <thead class="table-light">
            <tr>
                <th>{{ __('User') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Comment') }}</th>
                <th>{{ __('Logged At') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($task->progressLogs as $log)
                <tr>
                    <td>{{ optional($log->user)->name }}</td>
                    <td>
                        <span class="badge bg-info">
                            {{ getStatusLabelBadge($log->status->name) }}
                        </span>
                    </td>
                    <td>{!! $log->comment ?? '-' !!}</td>
                    <td>
                        {{ short_date_format_with_day($log->logged_at) }},
                        {{ time_date_format($log->logged_at) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">
                        {{ __('No progress logs found') }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
