<div class="row mb-4">
    @php
        $now = now();
        $endDate = \Carbon\Carbon::parse($task->end_date);
        $assignedCount = $task->assignments->count();

        if ($task->completed_at) {
            $progress = 100;
        } elseif ($assignedCount == 0) {
            $progress = 0;
        } else {
            $progress = $task->progress_percent ?? 0;
        }
    @endphp

    {{-- Assigned Users --}}
    <div class="col-md-6 col-lg-3">
        <div class="card shadow-sm border-start border-primary h-100">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <small class="text-muted d-block text-uppercase small fw-bold">{{ __('Assigned Users') }}</small>
                    <h3 class="mb-0 fw-bold text-primary">
                        {{ $assignedCount }}
                    </h3>
                </div>
                <div class="bg-light-primary p-2 rounded">
                    <i class="material-icons-outlined text-primary" style="font-size: 32px;">group</i>
                </div>
            </div>
        </div>
    </div>

    {{-- Start Date --}}
    <div class="col-md-6 col-lg-3">
        <div class="card shadow-sm border-start border-info h-100">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <small class="text-muted d-block text-uppercase small fw-bold">{{ __('Start Date') }}</small>
                    <h5 class="mb-0 fw-bold">
                        {{ short_date_format($task->start_date) }}
                    </h5>
                </div>
                <div class="bg-light-info p-2 rounded">
                    <i class="material-icons-outlined text-info" style="font-size: 32px;">event</i>
                </div>
            </div>
        </div>
    </div>

    {{-- Time Remaining --}}
    <div class="col-md-6 col-lg-3">
        <div class="card shadow-sm border-start border-warning h-100">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <small class="text-muted d-block text-uppercase small fw-bold">{{ __('Time Status') }}</small>
                    <h5 class="mb-0 fw-bold">
                        @if($task->completed_at)
                            <span class="badge bg-success">Finished</span>
                        @elseif($now->gt($endDate))
                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ __('Overdue') }}</span>
                        @else
                            {{ floor($now->diffInDays($endDate)) }} {{ __('Days Left') }}
                        @endif
                    </h5>
                </div>
                <div class="bg-light-warning p-2 rounded">
                    <i class="material-icons-outlined text-warning" style="font-size: 32px;">schedule</i>
                </div>
            </div>
        </div>
    </div>

    {{-- Work Progress --}}
    <div class="col-md-6 col-lg-3">
        <div class="card shadow-sm border-start border-success h-100">
            <div class="card-body">
                <small class="text-muted d-block text-uppercase small fw-bold">{{ __('Work Progress') }}</small>
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <h5 class="mb-0 fw-bold text-success">{{ $progress }}%</h5>
                    @if($assignedCount == 0)
                        <span class="badge bg-soft-secondary text-secondary small" style="font-size: 10px;">Waiting Assignment</span>
                    @endif
                </div>
                <div class="progress" style="height: 8px; border-radius: 10px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                         role="progressbar"
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
