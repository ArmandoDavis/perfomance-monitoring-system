@extends('layouts.admin.app')
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
                                @can('task.update')
                                    {{-- Edit --}}
                                    @if($task->status->reference == "SCS002")
                                        <a href="{{ route('admin_panel.tasks.edit', $task->uuid) }}" class="btn btn-sm btn-primary mb-2 me-2">
                                            <i class="material-icons-outlined">edit</i> {{ __('Edit') }}
                                        </a>

                                        <form action="{{ route('admin_panel.tasks.change_status', $task->uuid) }}" method="POST" class="d-none confirm-form-complete-{{ $task->uuid }}">
                                            @csrf
                                            @method('PUT')

                                            <input type="hidden" name="action_type" value="6">
                                            <input type="hidden" name="action" value="complete">
                                        </form>

                                        <a href="javascript:void(0)" class="btn btn-sm btn-warning text-white mb-2 me-2" onclick="formActionConfirmation('complete-{{ $task->uuid }}', '{{ __('complete') }}' )">
                                            {{ __('Complete') }}
                                        </a>
                                    @endif

                                    {{-- Change status --}}
                                    @if($task->status->reference == "SCS005" && $task->status->reference == "SCS006")
                                        @if($task->is_active)
                                            {{-- Deactivate form --}}
                                            <form action="{{ route('admin_panel.tasks.change_status', $task->uuid) }}" method="POST" class="d-none confirm-form-deactivate-{{ $task->uuid }}">
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
                                            <form action="{{ route('admin_panel.tasks.change_status', $task->uuid) }}" method="POST" class="d-none confirm-form-activate-{{ $task->uuid }}">
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

                                    @if($task->status->reference == "SCS004")
                                        <form action="{{ route('admin_panel.tasks.change_status', $task->uuid) }}" method="POST" class="d-none confirm-form-complete-{{ $task->uuid }}">
                                            @csrf
                                            @method('PUT')

                                            <input type="hidden" name="action_type" value="6">
                                            <input type="hidden" name="action" value="complete">
                                        </form>

                                        <a href="javascript:void(0)" class="btn btn-sm btn-warning text-white mb-2 me-2" onclick="formActionConfirmation('complete-{{ $task->uuid }}', '{{ __('complete') }}' )">
                                            {{ __('Complete') }}
                                        </a>
                                    @endif
                                @endcan


                                {{-- delete task --}}
                                @can('task.delete')
                                    @if($task->can_be_deleted)
                                        <form class="confirm-form-delete-{{ $task->uuid }}" action="{{ route('admin_panel.tasks.delete', $task->uuid) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <a href="javascript:void(0)" class="btn btn-sm btn-danger mr-2 mb-2" onclick="formActionConfirmation('delete-{{ $task->uuid }}', '{{ __('Delete') }}')">
                                            <i class="material-icons-outlined">delete</i> {{__('Delete')}}
                                        </a>
                                    @endif
                                @endcan

                                <a href="{{ route('admin_panel.tasks.index') }}" class="btn btn-sm btn-dark mr-2 mb-2">
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
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                                    <i class="material-icons-outlined">upload</i>
                                    {{ __('Upload Document') }}
                                </button>
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
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
                                    <i class="material-icons-outlined">add</i>
                                    {{ __('Add Expense') }}
                                </button>
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
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addAssignmentModal">
                                    <i class="material-icons-outlined">add</i> {{ __('Assign User') }}
                                </button>
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
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addPerformanceModal">
                                    <i class="material-icons-outlined">add</i>
                                    {{ __('Evaluate') }}
                                </button>
                            @endcan
                        </div>

                        @include('pages.admin.task.profile.includes.task_performance')
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('pages.admin.task.profile.includes.modal')
@endsection
