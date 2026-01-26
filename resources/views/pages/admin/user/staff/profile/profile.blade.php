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

                                    {{-- Resend password --}}
                                    <form class="confirm-form-resend-{{ $user->uuid }}" action="{{ route('admin_panel.users.resend_resend_temp_password', $user->uuid) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('POST')
                                        <input type="hidden" name="email" value="{{ $user->email }}" required>
                                    </form>
                                    <a href="javascript:void(0)" class="btn btn-sm btn-warning mb-2 mr-2" onclick="formActionConfirmation('resend-{{ $user->uuid }}', '{{ __('Resend password') }}')">
                                        <i class="fas fa-plane me-1"></i> <span>{{ __('Resend password') }}</span>
                                    </a>

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
@endsection

@push('scripts')
    <script>
        $(document).on('change', '.user-status-toggle', function () {
            let userId = $(this).data('id');
            let isChecked = $(this).is(':checked');
            let switchElem = $(this);

            if (!isChecked) {
                // Confirm before disabling
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will disable the user account.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, disable it'
                }).then((result) => {
                    if (result.isConfirmed) {
                        updateUserStatus(userId, 0, switchElem);
                    } else {
                        switchElem.prop('checked', true); // revert if cancelled
                    }
                });
            } else {
                // Enable directly
                updateUserStatus(userId, 1, switchElem);
            }
        });

        function updateUserStatus(userId, is_active, switchElem) {
            $.ajax({
                url: '/admin_panel/users/staff/toggle_status',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    user_id: userId,
                    is_active: is_active
                },
                success: function (res) {
                    if (res.success) {
                        toastMessage("success", res.message);
                    } else {
                        toastMessage("error", res.message);
                        switchElem.prop('checked', !is_active); // revert toggle
                    }
                },
                error: function (xhr, status, error) {
                    let response = xhr.responseJSON;

                    if (response && response.message) {
                        if(response.success) {
                            toastMessage("success", response.message);
                        } else {
                            toastMessage("error", response.message);
                        }
                    } else if (xhr.status === 403) {
                        toastMessage("error", "{{ __('unauthorized action') }}")
                    } else {
                        toastMessage("error", "{{ __('Something went wrong') }}")
                    }
                    switchElem.prop('checked', !is_active); // revert if error
                }
            });
        }

        async function toastMessage(type = 'error', message) {
            toastr[type](message, '', {
                timeOut: 3000,
                positionClass: 'toast-top-right',
                progressBar: true,
                closeButton: true
            });
        }
    </script>
@endpush
