@extends('layouts.hod.app')
@section('title', __('Task profile'))
@include('includes.assets.sweetalert_assets')
@include('includes.assets.confirm_alert_assets')
@include('includes.assets.ckeditor5_assets')
@include('includes.assets.select2_assets')
@include('includes.assets.maskmoney_assets')

@section('content')
    <div class="card">
        <div class="card-body">
            @include('pages.admin.task.profile.includes.stats')

            <ul class="nav nav-tabs mb-3" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link fw-medium active" data-bs-toggle="tab" href="#general_tab" role="tab" aria-selected="true">
                        {{ __('General') }}
                    </a>
                </li>

                <li class="nav-item" role="presentation">
                    <a class="nav-link fw-medium" data-bs-toggle="tab" href="#documents_tab" role="tab" aria-selected="false">
                        {{ __('Document center') }}
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link fw-medium" data-bs-toggle="tab" href="#expense_tab" role="tab">
                        {{ __('Expenses') }}
                    </a>
                </li>

                <li class="nav-item" role="presentation">
                    <a class="nav-link fw-medium" data-bs-toggle="tab" href="#assignment_tab" role="tab" aria-selected="false">
                        {{ __('Assignments') }}
                    </a>
                </li>

                <li class="nav-item" role="presentation">
                    <a class="nav-link fw-medium" data-bs-toggle="tab" href="#progress_tab" role="tab" aria-selected="false">
                        {{ __('Progress Logs') }}
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#performance_tab">
                        {{ __('Performance') }}
                    </a>
                </li>
            </ul>

            <div class="tab-content text-muted">
                {{-- taks general informations --}}
                <div class="tab-pane fade show active" id="general_tab" role="tabpanel">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-end flex-wrap gap-2">
                                @can('update', $task)
                                    @if($task->status->reference != "SCS004" && $task->status->reference != "SCS005")
                                        <a href="{{ route('hod_panel.tasks.edit', $task->uuid) }}" class="btn btn-sm btn-primary mb-2 me-2">
                                            <i class="material-icons-outlined">edit</i> {{ __('Edit') }}
                                        </a>
                                    @endif
                                @endcan


                                @can('updateStatus', $task)
                                    {{-- TODO -> IN PROGRESS --}}
                                    @if($task->status->reference == "SCS002")
                                        <form action="{{ route('hod_panel.tasks.change_status', $task->uuid) }}" method="POST" class="d-none confirm-form-start-{{ $task->uuid }}">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="action_type" value="6">
                                            <input type="hidden" name="action" value="start_progress">
                                        </form>
                                        <a href="javascript:void(0)" class="btn btn-sm btn-info text-white mb-2 me-2" onclick="formActionConfirmation('start-{{ $task->uuid }}', '{{ __('Start Working') }}' )">
                                            <i class="material-icons-outlined">play_arrow</i> {{ __('Start Task') }}
                                        </a>
                                    @endif

                                    {{-- IN PROGRESS -> SUBMITTED --}}
                                    @if($task->status->reference == "SCS003")
                                        <form action="{{ route('hod_panel.tasks.change_status', $task->uuid) }}" method="POST" class="d-none confirm-form-submit-{{ $task->uuid }}">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="action_type" value="6">
                                            <input type="hidden" name="action" value="submit_task">
                                        </form>
                                        <a href="javascript:void(0)" class="btn btn-sm btn-primary mb-2 me-2" onclick="formActionConfirmation('submit-{{ $task->uuid }}', '{{ __('Submit for Review') }}' )">
                                            <i class="material-icons-outlined">send</i> {{ __('Submit Work') }}
                                        </a>
                                    @endif
                                @endcan

                                @can('manage', $task)
                                    {{-- 3. SUBMITTED -> DONE --}}
                                    @if($task->status->reference == "SCS004")
                                        <form action="{{ route('hod_panel.tasks.change_status', $task->uuid) }}" method="POST" class="d-none confirm-form-complete-{{ $task->uuid }}">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="action_type" value="6">
                                            <input type="hidden" name="action" value="complete">
                                        </form>
                                        <a href="javascript:void(0)" class="btn btn-sm btn-success mb-2 me-2" onclick="formActionConfirmation('complete-{{ $task->uuid }}', '{{ __('Mark as Done') }}' )">
                                            <i class="material-icons-outlined">check_circle</i> {{ __('Approve & Complete') }}
                                        </a>

                                        <form action="{{ route('hod_panel.tasks.change_status', $task->uuid) }}" method="POST" class="d-none confirm-form-rejection-{{ $task->uuid }}">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="action_type" value="6">
                                            <input type="hidden" name="action" value="reject">
                                        </form>
                                        <a href="javascript:void(0)" class="btn btn-sm btn-outline-danger mb-2 me-2" onclick="formActionConfirmation('rejection-{{ $task->uuid }}', '{{ __('Reject & Redo') }}' )">
                                            {{ __('Needs Revision') }}
                                        </a>
                                    @endif
                                @endcan

                                @can('rollback', $task)
                                    {{-- UNDO COMPLETION within 48 hours --}}
                                    @if($task->status->reference == "SCS005" && $task->completed_at)
                                        @if($hoursSinceCompletion <= 48)
                                            <form action="{{ route('hod_panel.tasks.change_status', $task->uuid) }}" method="POST" class="d-none confirm-form-undo-{{ $task->uuid }}">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="action_type" value="6">
                                                <input type="hidden" name="action" value="undo_complete">
                                            </form>
                                            <a href="javascript:void(0)" class="btn btn-sm btn-outline-warning mb-2 me-2" onclick="formActionConfirmation('undo-{{ $task->uuid }}', '{{ __('Undo Completion') }}' )">
                                                <i class="material-icons-outlined">undo</i> {{ __('Undo Done') }}
                                            </a>
                                        @endif
                                    @endif
                                @endcan

                                {{-- TRANSFER & SHARE --}}
                                @can('transfer', $task)
                                    @if($task->status->reference != "SCS004" && $task->status->reference != "SCS005")
                                        <button class="btn btn-sm btn-outline-secondary mb-2" data-bs-toggle="modal" data-bs-target="#transferModal">
                                            <i class="material-icons-outlined">swap_horiz</i> {{ __('Transfer') }}
                                        </button>
                                    @endif
                                @endcan

                                @can('share', $task)
                                    <button class="btn btn-sm btn-outline-dark mb-2" data-bs-toggle="modal" data-bs-target="#shareModal">
                                        <i class="material-icons-outlined">share</i> {{ __('Share') }}
                                    </button>
                                @endcan

                                @can('updateStatus', $task)
                                    {{-- Change status --}}
                                    @if($task->status->reference == "SCS005" && $task->status->reference == "SCS006" || $task->status->reference == "SCS002")
                                        @if($task->is_active)
                                            {{-- Deactivate form --}}
                                            <form action="{{ route('hod_panel.tasks.change_status', $task->uuid) }}" method="POST" class="d-none confirm-form-deactivate-{{ $task->uuid }}">
                                                @csrf
                                                @method('PUT')

                                                <input type="hidden" name="action_type" value="6">
                                                <input type="hidden" name="action" value="deactivate">
                                            </form>

                                            <a href="javascript:void(0)" class="btn btn-sm btn-danger mb-2 me-2" onclick="formActionConfirmation('deactivate-{{ $task->uuid }}', '{{ __('Deactivate') }}' )">
                                                <i class="material-icons-outlined">update</i>
                                                {{ __('Deactivate') }}
                                            </a>
                                        @else
                                            {{-- Activate form --}}
                                            <form action="{{ route('hod_panel.tasks.change_status', $task->uuid) }}" method="POST" class="d-none confirm-form-activate-{{ $task->uuid }}">
                                                @csrf
                                                @method('PUT')

                                                <input type="hidden" name="action_type" value="6">
                                                <input type="hidden" name="action" value="activate">
                                            </form>

                                            <a href="javascript:void(0)" class="btn btn-sm btn-info mb-2 me-2" onclick="formActionConfirmation('activate-{{ $task->uuid }}', '{{ __('Activate') }}'  )">
                                                <i class="material-icons-outlined">update</i>
                                                {{ __('Activate') }}
                                            </a>
                                        @endif
                                    @endif
                                @endcan


                                {{-- delete task --}}
                                @can('delete', $task)
                                    @if($task->can_be_deleted)
                                        <form class="confirm-form-delete-{{ $task->uuid }}" action="{{ route('hod_panel.tasks.delete', $task->uuid) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <a href="javascript:void(0)" class="btn btn-sm btn-danger mr-2 mb-2" onclick="formActionConfirmation('delete-{{ $task->uuid }}', '{{ __('Delete') }}')">
                                            <i class="material-icons-outlined">delete</i> {{__('Delete')}}
                                        </a>
                                    @endif
                                @endcan

                                <a href="{{ route('hod_panel.tasks.index') }}" class="btn btn-sm btn-dark mr-2 mb-2">
                                    <i class="fas fa-close me-1"></i> {{ __('Close') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    @include('pages.admin.task.profile.includes.general_info')
                </div>

                <div class="tab-pane fade" id="documents_tab" role="tabpanel">
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <strong>{{ __('Task Documents') }}</strong>

                            @can('task.update')
                                @if($task->status->reference == "SCS002")
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                                        <i class="material-icons-outlined">upload</i>
                                        {{ __('Upload Document') }}
                                    </button>
                                @endif
                            @endcan
                        </div>

                        @include('pages.admin.task.profile.includes.task_documents')
                    </div>
                </div>


                <div class="tab-pane fade" id="expense_tab" role="tabpanel">
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <strong>{{ __('Task Expenses') }}</strong>

                            @can('task.update')
                                @if($task->status->reference == "SCS002")
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
                                        <i class="material-icons-outlined">add</i>
                                        {{ __('Add Expense') }}
                                    </button>
                                @endif
                            @endcan
                        </div>

                        @include('pages.admin.task.profile.includes.task_expense')
                    </div>
                </div>
                {{-- end of expense --}}


                <div class="tab-pane fade" id="assignment_tab" role="tabpanel">
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <strong>{{ __('Task Assignments') }}</strong>
                            @can('task.assign')
                                @if($task->status->reference == "SCS002")
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addAssignmentModal">
                                        <i class="material-icons-outlined">add</i> {{ __('Assign User') }}
                                    </button>
                                @endif
                            @endcan
                        </div>
                    </div>

                    @include('pages.admin.task.profile.includes.task_assignment')
                </div>

                <div class="tab-pane fade" id="progress_tab" role="tabpanel">
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <strong>{{ __('Task Progress') }}</strong>
                        </div>

                        @include('pages.admin.task.profile.includes.task_progress_logs')
                    </div>
                </div>

                <div class="tab-pane fade" id="performance_tab" role="tabpanel">
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <strong>{{ __('Performance Scores') }}</strong>

                            @can('performance.evaluate')
                                @if($userAssigned->isNotEmpty())
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addPerformanceModal">
                                        <i class="material-icons-outlined">add</i>
                                        Evaluate
                                    </button>
                                @else
                                    <div class="alert alert-warning mb-0">
                                        All users have been evaluated
                                    </div>
                                @endif
                            @endcan
                        </div>

                        @include('pages.admin.task.profile.includes.task_performance')
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('pages.hod.task.profile.includes.modal')
@endsection
