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
                        <tr>
                            <th>{{ __('Is Active?') }}</th>
                            <td>{!! getBooleanBadge($task->is_active) !!} </td>
                        </tr>
                        <tr>
                            <th>{{ __('Is Transferred') }}</th>
                            <td>{!! getBooleanBadge($task->is_transferred) !!} </td>
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

                        <tr>
                            <th>{{ __('Task Context') }}</th>
                            <td>
                                @if($userShare)
                                    {{-- Inayoonekana kwa Staff aliyeshirikishwa (The Peer) --}}
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-warning text-dark me-2">
                                            <i class="fas fa-handshake me-1"></i> {{ __('Shared with me') }}
                                        </span>
                                        <small class="text-muted">
                                            {{ __('by') }} <strong>{{ $userShare->sharedWithUser->name ?? __('Unknown') }}</strong>
                                        </small>
                                    </div>
                                @elseif($task->shares->isNotEmpty())
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-info text-dark me-2">
                                            <i class="fas fa-users me-1"></i> {{ __('Shared Task') }}
                                        </span>
                                        <small class="text-muted">
                                            ({{ __('Distributed to') }} {{ $task->shares->count() }} {{ __('person(s)') }})
                                        </small>
                                    </div>
                                @else
                                    <span class="badge bg-light text-muted border">
                                        <i class="fas fa-user-lock me-1"></i> {{ __('Private') }}
                                    </span>
                                @endif
                            </td>
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

    {{-- Task Shares Section --}}
    <div class="col-md-12 my-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h6 class="mb-0 text-muted font-weight-bold">
                    <i class="fas fa-project-diagram me-1"></i> {{ __('Task Access Distribution') }}
                </h6>
                @can('share', $task)
                    <div class="btn-group" hidden>
                        <button type="button" class="btn btn-primary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#shareTaskModal">
                            <i class="fas fa-plus-circle me-1"></i> {{ __('Grant New Access') }}
                        </button>
                    </div>
                @endcan
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                        <tr>
                            <th class="ps-3">{{ __('Recipient') }}</th>
                            <th>{{ __('Recipient Type') }}</th>
                            <th>{{ __('Access Level') }}</th>
                            <th>{{ __('Shared By') }}</th>
                            <th>{{ __('Remarks') }}</th>
                            <th>{{ __('Date') }}</th>
                            <th class="text-center">{{ __('Action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($task->shares as $share)
                            <tr>
                                <td class="ps-3">
                                    @if($share->shared_with_user_id)
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs me-2">
                                                <span class="avatar-title rounded-circle bg-soft-primary text-primary small">
                                                    {{ strtoupper(substr($share->sharedWithUser->name, 0, 1)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $share->sharedWithUser->name }}</div>
                                                <div class="small text-muted">{{ $share->sharedWithUser->department->name ?? '-' }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs me-2">
                                                <span class="avatar-title rounded-circle bg-soft-info text-info small">
                                                    <i class="fas fa-building"></i>
                                                </span>
                                            </div>
                                            <div class="fw-bold">{{ $share->sharedWithDepartment->name }}</div>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $share->shared_with_user_id ? 'bg-soft-primary text-primary' : 'bg-soft-info text-info' }}">
                                        {{ $share->shared_with_user_id ? __('Staff') : __('Department') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ strtolower(optional($share->accessLevel)->reference) == 'ACL002' ? 'bg-danger' : 'bg-success' }}">
                                        {{ optional($share->accessLevel)->name }}
                                    </span>
                                </td>

                                <td><small class="text-muted">{{ $share->sharedBy->name }}</small></td>
                                <td><small class="text-truncate d-inline-block" style="max-width: 150px;">{{ $share->remarks ?? '-' }}</small></td>
                                <td><small>{{ short_date_format($share->created_at) }}</small></td>
                                <td class="text-end pe-3">
                                    <div class="d-flex justify-content-end gap-2">
                                        {{-- Toggle Access Level --}}
                                        @php
                                            $isViewOnly = $share->accessLevel->reference == "ACL001";
                                            $targetRef = $isViewOnly ? "ACL002" : "ACL001";
                                            $btnClass = $isViewOnly ? "text-success" : "text-warning";
                                            $btnText = $isViewOnly ? __('Grant Edit Access') : __('Restrict to View Only');
                                            $icon = $isViewOnly ? "fa-user-edit" : "fa-user-shield";
                                        @endphp
                                        <form class="confirm-form-modify-{{ $share->uuid }}" action="{{ route('hod_panel.tasks.share.modify_access', $share->uuid) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="target_reference" value="{{ $targetRef }}">
                                        </form>
                                        <a href="javascript:void(0)" class="btn btn-link {{ $btnClass }} p-0" onclick="formActionConfirmation('modify-{{ $share->uuid }}', '{{ $btnText }}')" title="{{ $btnText }}">
                                            <i class="fas {{ $icon }}"></i>
                                        </a>


                                        {{-- Revoke/Delete Access --}}
                                        <form class="confirm-form-delete-share-{{ $share->uuid }}" action="{{ route('hod_panel.tasks.share.delete', $share->uuid) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <a href="javascript:void(0)" class="btn btn-link {{ $btnClass }} p-0" onclick="formActionConfirmation('delete-share-{{ $share->uuid }}', '{{ __('Revoke Access') }}')" title="{{ __('Revoke Access') }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <img src="{{ asset('assets/images/empty-share.svg') }}" alt="" class="mb-3" style="width: 80px;">
                                    <p>{{ __('This task is currently private to your department.') }}</p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
