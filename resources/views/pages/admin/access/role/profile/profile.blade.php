@extends('layouts.admin.app')
@section('title', __('Role profile'))
@include('includes.assets.sweetalert_assets')
@include('includes.assets.confirm_alert_assets')

@section('content')
    <div class="container-fluid">
        <div id="content">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs nav-border-top nav-border-top-primary mb-3" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link fw-medium active" data-bs-toggle="tab" href="#general_tab" role="tab" aria-selected="true">
                                {{ __('General Info') }}
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content text-muted">
                        <div class="tab-pane fade show active" id="general_tab" role="tabpanel">
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <div class="float-end">
                                        <a href="{{ route('admin_panel.role.edit', $role->uuid) }}" class="btn btn-sm btn-primary">
                                            <i class="ri-pencil-fill me-2 text-white-50"></i> {{ __('Edit') }}
                                        </a>

                                        @if($role->can_be_deleted)
                                            <form class="confirm-form-delete-{{ $role->uuid }}" action="{{ route('admin_panel.role.delete', $role->uuid) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>

                                            <a href="javascript:void(0)" class="btn btn-sm btn-danger" onclick="formActionConfirmation('delete-{{ $role->uuid }}', '{{ __('Delete') }}')">
                                                <i class="ri-trash me-2 text-white-50"></i>{{__('Delete')}}
                                            </a>
                                        @endif

                                        <a href="{{ route('admin_panel.role.index') }}" class="btn btn-sm btn-dark">
                                            <i class="ri-close me-2 text-white-50"></i> {{ __('Close') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @include('pages.admin.access.role.profile.includes.general_info')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
