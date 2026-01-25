<div class="card-body p-0">
    <table class="table table-bordered mb-0">
        <thead>
            <tr>
                <th>{{ __('Staff') }}</th>
                <th>{{ __('Timeliness') }}</th>
                <th>{{ __('Quality') }}</th>
                <th>{{ __('Budget') }}</th>
                <th>{{ __('KPI') }}</th>
                <th>{{ __('Total') }}</th>
                <th>{{ __('Evaluated By') }}</th>
                <th>{{ __('Date') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($task->performanceScores as $score)
                <tr>
                    <td>{{ $score->user->name }}</td>
                    <td>{{ $score->timeliness_score }}</td>
                    <td>{{ $score->quality_score }}</td>
                    <td>{{ $score->budget_score }}</td>
                    <td>{{ $score->kpi_score }}</td>
                    <td>
                        <span class="badge bg-success">
                            {{ number_2_format($score->total_score) }}
                        </span>
                    </td>
                    <td>{{ $score->evaluator->name }}</td>
                    <td>{{ short_date_format_with_day($score->created_at) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">
                        {{ __('No performance records yet') }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
