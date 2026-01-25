<div class="row">
    <!-- General Info -->
    <div class="col-md-7">
        <div class="card mb-3">
            <div class="card-header text-muted">
                <strong>{{ __('General Info') }}</strong>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <tbody>
                        {{-- Title --}}
                        <tr>
                            <th>{{ __('Title') }}</th>
                            <td>{{ $task->title }}</td>
                        </tr>

                        {{-- Description --}}
                        <tr>
                            <th>{{ __('Description') }}</th>
                            <td>{!! $task->description ?? '-' !!}</td>
                        </tr>

                        {{-- Department --}}
                        <tr>
                            <th>{{ __('Department') }}</th>
                            <td>{{ optional($task->department)->name ?? '-' }}</td>
                        </tr>

                        {{-- Status --}}
                        <tr>
                            <th>{{ __('Status') }}</th>
                            <td>{!! getStatusLabelBadge($task->status->name) ?? '-' !!} </td>
                        </tr>

                        {{-- Budget --}}
                        <tr>
                            <th>{{ __('Allocated Budget') }}</th>
                            <td>{{ number_2_format($task->allocated_budget) }}</td>
                        </tr>

                        <tr>
                            <th>{{ __('Spent Amount') }}</th>
                            <td>{{ number_2_format($task->spent_amount) }}</td>
                        </tr>

                        <tr>
                            <th>{{ __('Remaining Budget') }}</th>
                            <td>{{ number_2_format($task->remaining_budget) }}</td>
                        </tr>

                        {{-- Active --}}
                        <tr>
                            <th>{{ __('Is Active?') }}</th>
                            <td>
                                @if($task->is_active)
                                    <span class="badge bg-primary">{{ __('Yes') }}</span>
                                @else
                                    <span class="badge bg-danger">{{ __('No') }}</span>
                                @endif
                            </td>
                        </tr>

                        {{-- Dates --}}
                        <tr>
                            <th>{{ __('Created At') }}</th>
                            <td>{{ short_date_format_with_day($task->created_at) }}, {{ time_date_format($task->created_at) }}</td>
                        </tr>

                        <tr>
                            <th>{{ __('Updated At') }}</th>
                            <td>{{ short_date_format_with_day($task->updated_at) }}, {{ time_date_format($task->updated_at) }}</td>
                        </tr>

                        <tr>
                            <th>{{ __('Start Date') }}</th>
                            <td>{{ $task->start_date ? short_date_format_with_day($task->start_date) : '-' }}</td>
                        </tr>

                        <tr>
                            <th>{{ __('End Date') }}</th>
                            <td>{{ $task->end_date ? short_date_format_with_day($task->end_date) : '-' }}</td>
                        </tr>

                        <tr>
                            <th>{{ __('Completed At') }}</th>
                            <td>{{ $task->completed_at ? short_date_format_with_day($task->completed_at) . ', ' . time_date_format($task->completed_at) : '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    {{-- comments --}}
    <div class="col-md-5">
        <div class="card mb-3">
            <div class="card-header text-muted">
                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('Comments') }}</strong>
                    </div>

                    <div class="col-md-6 text-end">
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addCommentModal-{{ $task->uuid }}">
                            <i class="material-icons-outlined">add</i> {{ __('Comment') }}
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                @if($task->comments->isEmpty())
                    <p class="p-3 mb-0 text-muted">{{ __('No comments yet.') }}</p>
                @else
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>{{ __('Commented By') }}</th>
                                <th>{{ __('Comment') }}</th>
                                <th>{{ __('Date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($task->comments as $comment)
                                <tr>
                                    <td>{{ $comment->user->name }}</td>
                                    <td>{!!  $comment->content  !!}</td>
                                    <td>{{ short_date_format_with_day($comment->created_at) }}, {{ time_date_format($comment->created_at) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>
