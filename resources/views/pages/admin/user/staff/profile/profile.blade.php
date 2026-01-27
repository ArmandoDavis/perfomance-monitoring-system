@extends('layouts.admin.app')
@section('title', __('Staff profile'))
@include('includes.assets.sweetalert_assets')
@include('includes.assets.confirm_alert_assets')

@section('content')
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs mb-3" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link fw-medium active" data-bs-toggle="tab" href="#general_tab" role="tab" aria-selected="true">
                        {{ __('General Info') }}
                    </a>
                </li>
            </ul>

            <div class="tab-content text-muted">
                <div class="tab-pane fade show active" id="general_tab" role="tabpanel">

                    {{-- Action section --}}
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-end flex-wrap gap-2">
                                    <a href="{{ route('admin_panel.users.edit', $user->uuid) }}" class="btn btn-sm btn-primary mb-2 mr-2">
                                        <i class="fas fa-edit me-1"></i> {{ __('Edit') }}
                                    </a>

                                    @if($user->is_active)
                                        {{-- Deactivate form --}}
                                        <form action="{{ route('admin_panel.users.change_status', $user->uuid) }}" method="POST" class="d-none confirm-form-deactivate-{{ $user->uuid }}">
                                            @csrf
                                            @method('PUT')

                                            <input type="hidden" name="action_type" value="6">
                                            <input type="hidden" name="action" value="deactivate">
                                        </form>

                                        <a href="javascript:void(0)" class="btn btn-sm btn-danger mb-2 me-2" onclick="formActionConfirmation('deactivate-{{ $user->uuid }}', '{{ __('Deactivate') }}' )">
                                            {{ __('Deactivate') }}
                                        </a>
                                    @else
                                        {{-- Activate form --}}
                                        <form action="{{ route('admin_panel.users.change_status', $user->uuid) }}" method="POST" class="d-none confirm-form-activate-{{ $user->uuid }}">
                                            @csrf
                                            @method('PUT')

                                            <input type="hidden" name="action_type" value="6">
                                            <input type="hidden" name="action" value="activate">
                                        </form>

                                        <a href="javascript:void(0)" class="btn btn-sm btn-info mb-2 me-2" onclick="formActionConfirmation('activate-{{ $user->uuid }}', '{{ __('Activate') }}'  )">
                                            {{ __('Activate') }}
                                        </a>
                                    @endif

                                    {{-- Resend password --}}
                                    @if($user->id != user_id())
                                        <a href="javascript:void(0)" class="btn btn-sm btn-warning mb-2 mr-2" data-bs-toggle="modal" data-bs-target="#uploadUpdatePasswordModal">
                                            <i class="fas fa-plane me-1"></i> <span>{{ __('Change password') }}</span>
                                        </a>
                                    @endif

                                    {{-- Delete --}}
                                    <form class="confirm-form-delete-{{ $user->uuid }}" action="{{ route('admin_panel.users.delete', $user->uuid) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <a href="javascript:void(0)" class="btn btn-sm btn-danger mb-2 mr-2" onclick="formActionConfirmation('delete-{{ $user->uuid }}', '{{ __('Delete') }}')">
                                        <i class="fas fa-trash-can-arrow-up me-1"></i> {{__('Delete')}}
                                    </a>

                                {{-- Close --}}
                                <a href="{{ route('admin_panel.users.index') }}" class="btn btn-sm btn-dark mb-2 mr-2" >
                                    <i class="fas fa-closed-captioning me-1"></i> {{ __('Close') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    @include('pages.admin.user.staff.profile.includes.general_info')
                </div>
            </div>
        </div>
    </div>

    @include('pages.admin.user.staff.profile.includes.modal')
@endsection
