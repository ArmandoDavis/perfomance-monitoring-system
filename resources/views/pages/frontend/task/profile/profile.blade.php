@extends('layouts.frontend.app')
@section('title', __('Task profile'))
@include('includes.assets.confirm_alert_assets')
@include('includes.assets.sweetalert_assets')
@include('includes.assets.ckeditor5_assets')
@include('includes.assets.select2_assets')
@include('includes.assets.maskmoney_assets')

@section('content')
    <div class="card">
        <div class="card-body">
            @include('pages.frontend.task.profile.includes.stats')

            <ul class="nav nav-tabs mb-3" role="tablist">
                <li class="nav-item"><a class="nav-link fw-medium active" data-bs-toggle="tab" href="#general_tab">{{ __('General') }}</a></li>
                <li class="nav-item"><a class="nav-link fw-medium" data-bs-toggle="tab" href="#my_team">{{ __('My Team') }}</a></li>
                <li class="nav-item"><a class="nav-link fw-medium" data-bs-toggle="tab" href="#documents_tab">{{ __('Documents') }}</a></li>
                <li class="nav-item"><a class="nav-link fw-medium" data-bs-toggle="tab" href="#expense_tab">{{ __('Expenses') }}</a></li>
                <li class="nav-item"><a class="nav-link fw-medium" data-bs-toggle="tab" href="#progress_tab">{{ __('Logs') }}</a></li>
            </ul>

            <div class="tab-content text-muted">
                {{-- GENERAL TAB --}}
                <div class="tab-pane fade show active" id="general_tab" role="tabpanel">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-end flex-wrap gap-2">
                                @can('updateStatus', $task)
                                    @if($task->status->reference == "SCS002")
                                        <form action="{{ route('frontend.tasks.change_status', $task->uuid) }}" method="POST" class="confirm-form-start-{{ $task->uuid }}">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="action" value="start_progress">
                                            <input type="hidden" name="action_type" value="6">
                                            <button type="button" class="btn btn-sm btn-info text-white" onclick="formActionConfirmation('start-{{ $task->uuid }}', '{{ __('Start Task') }}')">
                                                <i class="material-icons-outlined">play_arrow</i> {{ __('Start Working') }}
                                            </button>
                                        </form>
                                    @endif

                                    @if($task->status->reference == "SCS003")
                                        <form action="{{ route('frontend.tasks.change_status', $task->uuid) }}" method="POST" class="confirm-form-submit-{{ $task->uuid }}">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="action" value="submit_task">
                                            <input type="hidden" name="action_type" value="6">
                                            <button type="button" class="btn btn-sm btn-primary" onclick="formActionConfirmation('submit-{{ $task->uuid }}', '{{ __('Submit Task') }}')">
                                                <i class="material-icons-outlined">send</i> {{ __('Submit for Review') }}
                                            </button>
                                        </form>
                                    @endif
                                @endcan

                                @can('share', $task)
                                    <button class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#shareModal">
                                        <i class="material-icons-outlined">share</i> {{ __('Share with Peer') }}
                                    </button>
                                @endcan

                                <a href="{{ route('frontend.tasks.index') }}" class="btn btn-sm btn-dark">
                                    <i class="fas fa-close me-1"></i> {{ __('Close') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    @include('pages.frontend.task.profile.includes.general_info')
                </div>

                {{-- MY TEAM TAB --}}
                <div class="tab-pane fade" id="my_team" role="tabpanel">
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <strong>{{ __('Team Members') }}</strong>
                        </div>
                        @include('pages.frontend.task.profile.includes.team_member')

                        <div class="card-body border-top">
                            <h6>{{ __('Shared With') }}</h6>
                            @include('pages.frontend.task.profile.includes.shared_users_list')
                        </div>
                    </div>
                </div>

                {{-- DOCUMENTS TAB --}}
                <div class="tab-pane fade" id="documents_tab" role="tabpanel">
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <strong>{{ __('Task Documents') }}</strong>
                            @if($task->status->reference !== "SCS005")
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                                    <i class="material-icons-outlined">upload</i> {{ __('Upload') }}
                                </button>
                            @endif
                        </div>
                        @include('pages.frontend.task.profile.includes.task_documents')
                    </div>
                </div>

                {{-- EXPENSES TAB --}}
                <div class="tab-pane fade" id="expense_tab" role="tabpanel">
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <strong>{{ __('My Expenses') }}</strong>
                            @if($task->status->reference !== "SCS005")
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addMyExpenseModal">
                                    <i class="material-icons-outlined">add</i> {{ __('Add Expense') }}
                                </button>
                            @endif
                        </div>
                        @include('pages.frontend.task.profile.includes.task_expense')
                    </div>
                </div>

                {{-- LOGS TAB --}}
                <div class="tab-pane fade" id="progress_tab" role="tabpanel">
                    @include('pages.frontend.task.profile.includes.task_progress_logs')
                </div>
            </div>
        </div>
    </div>

    @include('pages.frontend.task.profile.includes.modal')
@endsection
