@extends('layouts.admin.app')
@section('title', 'Department Profile')

@include('includes.assets.sweetalert_assets')
@include('includes.assets.confirm_alert_assets')

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-custom mb-3" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link fw-medium active" data-bs-toggle="tab" href="#general_tab" role="tab" aria-selected="true">
                        General Info
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link fw-medium" data-bs-toggle="tab" href="#workers_tab" role="tab" aria-selected="false">
                        Workers <span class="badge bg-soft-primary text-primary ms-1">{{ $department->users_count ?? $department->users->count() }}</span>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link fw-medium" data-bs-toggle="tab" href="#tasks_tab" role="tab" aria-selected="false">
                        Task List
                    </a>
                </li>
            </ul>

            <div class="tab-content text-muted">

                <div class="tab-pane fade show active" id="general_tab" role="tabpanel">
                    {{-- Action section --}}
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-end flex-wrap gap-2">
                                <a href="{{ route('admin_panel.departments.edit', $department->uuid) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </a>

                                @if($department->is_active)
                                    <form action="{{ route('admin_panel.departments.change_status', $department->uuid) }}" method="POST" class="d-none confirm-form-deactivate-{{ $department->uuid }}">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="action_type" value="6">
                                        <input type="hidden" name="action" value="deactivate">
                                    </form>
                                    <a href="javascript:void(0)" class="btn btn-sm btn-outline-danger" onclick="formActionConfirmation('deactivate-{{ $department->uuid }}', 'Deactivate')">
                                        Deactivate
                                    </a>
                                @else
                                    <form action="{{ route('admin_panel.departments.change_status', $department->uuid) }}" method="POST" class="d-none confirm-form-activate-{{ $department->uuid }}">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="action_type" value="6">
                                        <input type="hidden" name="action" value="activate">
                                    </form>
                                    <a href="javascript:void(0)" class="btn btn-sm btn-outline-info" onclick="formActionConfirmation('activate-{{ $department->uuid }}', 'Activate')">
                                        Activate
                                    </a>
                                @endif

                                @if($department->can_be_deleted)
                                    <form class="confirm-form-delete-{{ $department->uuid }}" action="{{ route('admin_panel.departments.delete', $department->uuid) }}" method="POST" style="display: none;">
                                        @csrf @method('DELETE')
                                    </form>
                                    <a href="javascript:void(0)" class="btn btn-sm btn-danger" onclick="formActionConfirmation('delete-{{ $department->uuid }}', 'Delete')">
                                        <i class="fas fa-trash me-1"></i> Delete
                                    </a>
                                @endif

                                <a href="{{ route('admin_panel.departments.index') }}" class="btn btn-sm btn-dark">
                                    <i class="fas fa-arrow-left me-1"></i> Close
                                </a>
                            </div>
                        </div>
                    </div>
                    @include('pages.admin.department.profile.includes.general_info')
                </div>

                <div class="tab-pane fade" id="workers_tab" role="tabpanel">
                    @include('pages.admin.department.profile.includes.workers_list')
                </div>

                <div class="tab-pane fade" id="tasks_tab" role="tabpanel">
                    @include('pages.admin.department.profile.includes.tasks_list')
                </div>

            </div>
        </div>
    </div>
@endsection
