<div class="row mb-4">
    @php
        $startDate = \Carbon\Carbon::parse($task->start_date);
        $endDate = \Carbon\Carbon::parse($task->end_date);
        $now = now();

        $totalDays = $startDate->diffInDays($endDate);
        $daysPassed = $startDate->diffInDays($now);

        $progress = $totalDays > 0 ? min(100, round(($daysPassed / $totalDays) * 100)) : 0;
    @endphp

    {{-- Assigned Users --}}
    <div class="col-md-6 col-lg-3">
        <div class="card shadow-sm border-start border-primary">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <small class="text-muted">{{ __('Assigned Users') }}</small>
                    <h3 class="mb-0 fw-bold">
                        {{ $task->assignments->count() }}
                    </h3>
                </div>
                <i class="material-icons-outlined text-primary" style="font-size: 36px;">group</i>
            </div>
        </div>
    </div>

    {{-- Start Date --}}
    <div class="col-md-6 col-lg-3">
        <div class="card shadow-sm border-start border-info">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <small class="text-muted">{{ __('Start Date') }}</small>
                    <h5 class="mb-0 fw-bold">
                        {{ short_date_format($task->start_date) }}
                    </h5>
                </div>
                <i class="material-icons-outlined text-info" style="font-size: 36px;">event</i>
            </div>
        </div>
    </div>

    {{-- Time Remaining --}}
    <div class="col-md-6 col-lg-3">
        <div class="card shadow-sm border-start border-warning">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <small class="text-muted">{{ __('Time Remaining') }}</small>
                    <h5 class="mb-0 fw-bold">
                        @if($now->gt($endDate))
                            <span class="text-danger">{{ __('Overdue') }}</span>
                        @else
                            {{ floor($now->diffInDays($endDate)) }} {{ __('Days Left') }}
                        @endif
                    </h5>
                </div>
                <i class="material-icons-outlined text-warning" style="font-size: 36px;">schedule</i>
            </div>
        </div>
    </div>

    {{-- Progress --}}
    <div class="col-md-6 col-lg-3">
        <div class="card shadow-sm border-start border-success">
            <div class="card-body">
                <small class="text-muted">{{ __('Progress') }}</small>
                <h5 class="mb-2 fw-bold">{{ $progress }}%</h5>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-success" role="progressbar"
                         style="width: {{ $progress }}%"
                         aria-valuenow="{{ $progress }}"
                         aria-valuemin="0"
                         aria-valuemax="100">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
