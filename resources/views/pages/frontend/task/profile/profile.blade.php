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
                <li class="nav-item" role="presentation">
                    <a class="nav-link fw-medium active" data-bs-toggle="tab" href="#general_tab" role="tab" aria-selected="true">
                        {{ __('General') }}
                    </a>
                </li>

                <li class="nav-item" role="presentation">
                    <a class="nav-link fw-medium" data-bs-toggle="tab" href="#my_team" role="tab" aria-selected="false">
                        {{ __('My Team') }}
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
                                @if($task->status->reference !== "SCS005")
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-sm btn-warning text-white dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            {{ __('Complete') }}
                                        </button>
                                        <ul class="dropdown-menu shadow">
                                            @foreach($statusActions as $status)
                                                <li>
                                                    <a href="javascript:void(0)"  class="dropdown-item"  onclick="submitStatusAction( '{{ route('frontend.tasks.update_status', [$task->uuid, $status->reference]) }}', '{{ $status->name }}'   )" >
                                                        {{ __($status->name) }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <a href="{{ route('frontend.tasks.index') }}" class="btn btn-sm btn-dark mr-2 mb-2">
                                    <i class="fas fa-close me-1"></i> {{ __('Close') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    @include('pages.frontend.task.profile.includes.general_info')
                </div>

                <div class="tab-pane fade" id="my_team" role="tabpanel">
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <strong>{{ __('Team Members') }}</strong>
                        </div>

                        @include('pages.frontend.task.profile.includes.team_member')
                    </div>
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

                        @include('pages.frontend.task.profile.includes.task_documents')
                    </div>
                </div>


                <div class="tab-pane fade" id="expense_tab" role="tabpanel">
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <strong>{{ __('Task Expenses') }}</strong>

                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addMyExpenseModal">
                                <i class="material-icons-outlined">add</i>
                                {{ __('Add Expense') }}
                            </button>
                        </div>

                        @include('pages.frontend.task.profile.includes.task_expense')
                    </div>
                </div>
                {{-- end of expense --}}

                <div class="tab-pane fade" id="progress_tab" role="tabpanel">
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <strong>{{ __('Task Progress') }}</strong>
                        </div>

                        @include('pages.frontend.task.profile.includes.task_progress_logs')
                    </div>
                </div>

                <div class="tab-pane fade" id="performance_tab" role="tabpanel">
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <strong>{{ __('Performance Scores') }}</strong>
                        </div>

                        @include('pages.frontend.task.profile.includes.task_performance')
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('pages.frontend.task.profile.includes.modal')
@endsection
